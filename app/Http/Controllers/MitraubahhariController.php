<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Mitra;
use App\Models\MitraUbahHari;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MitraubahhariController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:mitraubah-list', only: ['index', 'fetch']),
            new Middleware('permission:mitraubah-create', only: ['create', 'store']),
            new Middleware('permission:mitraubah-edit', only: ['edit', 'update']),
            new Middleware('permission:mitraubah-show', only: ['show']),
            new Middleware('permission:mitraubah-delete', only: ['delete', 'destroy']),
        ];
    }

    public function db_switch($sw)
    {
        if ($sw == 2) {
            Config::set('database.connections.mysql.database', config('custom.db02_dbname'));
            Config::set('database.connections.mysql.username', config('custom.db02_username'));
            Config::set('database.connections.mysql.password', config('custom.db02_password'));
        } elseif ($sw == 1) {
            Config::set('database.connections.mysql.database', config('custom.db01_dbname'));
            Config::set('database.connections.mysql.username', config('custom.db01_username'));
            Config::set('database.connections.mysql.password', config('custom.db01_password'));
        }

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('mitraubah_pp')) {
            $request->session()->put('mitraubah_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('mitraubah_show')) {
            $request->session()->put('mitraubah_show', '0');
        }
        if (!$request->session()->exists('mitraubah_branch_id')) {
            $request->session()->put('mitraubah_branch_id', 'all');
        }
        if (!$request->session()->exists('mitraubah_user_id')) {
            $request->session()->put('mitraubah_user_id', 'all');
        }
        if (!$request->session()->exists('mitraubah_tanggal')) {
            $request->session()->put('mitraubah_tanggal', '_');
        }

        $search_arr = ['mitraubah_show', 'mitraubah_branch_id', 'mitraubah_user_id', 'mitraubah_tanggal'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $users = User::where('approved', 1)->orderBy('name')->pluck('name', 'id');
        $datas = MitraUbahHari::join('branches', 'branches.id', '=', 'mitra_ubah_haris.branch_id')
            ->join('users', 'users.id', '=', 'mitra_ubah_haris.user_id')
            ->select('mitra_ubah_haris.*', 'branches.nama as branch_nama', 'users.name as user_nama');

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('mitraubah_'));

            if ($search_arr[$i] == 'mitraubah_show') {
                if (session($search_arr[$i]) == '0') {
                    $datas = $datas->where('mitra_ubah_haris.approved_hrd', 0);
                }
            } else if ($search_arr[$i] == 'mitraubah_branch_id' || $search_arr[$i] == 'mitraubah_user_id') {
                if (session($search_arr[$i]) != 'all') {
                    $datas = $datas->where([$field => session($search_arr[$i])]);
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }

        // $sql = $datas->toSql();
        // $bindings = $datas->getBindings();
        // foreach ($bindings as $binding) {
        //     $sql = preg_replace('/\?/', "'" . addslashes($binding) . "'", $sql, 1);
        // }
        // dd($sql);

        // $datas = $datas->where('user_id', auth()->user()->id);
        $datas = $datas->latest()->paginate(session('mitraubah_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('mitraubah.index', compact(['datas', 'branches', 'users']))->with('i', (request()->input('page', 1) - 1) * session('mitraubah_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('mitraubah_pp', $request->pp);
        $request->session()->put('mitraubah_show', $request->show);
        $request->session()->put('mitraubah_branch_id', $request->branch);
        $request->session()->put('mitraubah_user_id', $request->user);
        $request->session()->put('mitraubah_tanggal', $request->tanggal);

        $search_arr = ['mitraubah_show', 'mitraubah_branch_id', 'mitraubah_user_id', 'mitraubah_tanggal'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $users = User::where('approved', 1)->orderBy('name')->pluck('name', 'id');
        $datas = MitraUbahHari::join('branches', 'branches.id', '=', 'mitra_ubah_haris.branch_id')
            ->join('users', 'users.id', '=', 'mitra_ubah_haris.user_id')
            ->select('mitra_ubah_haris.*', 'branches.nama as branch_nama', 'users.name as user_nama');

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('mitraubah_'));

            if ($search_arr[$i] == 'mitraubah_show') {
                if (session($search_arr[$i]) == '0') {
                    $datas = $datas->where('mitra_ubah_haris.approved_hrd', 0);
                }
            } else if ($search_arr[$i] == 'mitraubah_branch_id' || $search_arr[$i] == 'mitraubah_user_id') {
                if (session($search_arr[$i]) != 'all') {
                    $datas = $datas->where([$field => session($search_arr[$i])]);
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }
        // $datas = $datas->where('user_id', auth()->user()->id);
        $datas = $datas->latest()->paginate(session('mitraubah_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        $datas->withPath('/human-resource/mitraubah'); // pagination url to

        $view = view('mitraubah.partials.table', compact(['datas', 'branches', 'users']))->with('i', (request()->input('page', 1) - 1) * session('mitraubah_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        //
    }

    public function edit(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $datas = MitraUbahHari::join('branches', 'branches.id', '=', 'mitra_ubah_haris.branch_id')
            ->join('users', 'users.id', '=', 'mitra_ubah_haris.user_id')
            ->select('mitra_ubah_haris.*', 'branches.nama as branch_nama', 'users.name as user_nama');
        $datas = MitraUbahHari::join('branches', 'branches.id', '=', 'mitra_ubah_haris.branch_id')
            ->join('users', 'users.id', '=', 'mitra_ubah_haris.user_id')
            ->select('mitra_ubah_haris.*', 'branches.nama as branch_nama', 'users.name as user_nama')
            ->where('mitra_ubah_haris.id', Crypt::decrypt($request->mitraubah))
            ->first();

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('mitraubah.edit', compact(['datas']));
    }

    public function update(Request $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $mitraubah = MitraUbahHari::find(Crypt::decrypt($request->mitraubah));

        if ($mitraubah) {
            $namauser = $mitraubah->user->name;
            $status = $request->input('status');

            $mitraubah->update([
                'approved_hrd' => $status,
            ]);

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $namauser);
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
    }

    public function delete(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        //
    }
}
