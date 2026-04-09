<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Requests\PcbiayaRequest;
use App\Models\JenisPengeluaranCabang;
use App\Models\PcBiaya;
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

class PcbiayaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:pcbiaya-list', only: ['index', 'fetch']),
            new Middleware('permission:pcbiaya-create', only: ['create', 'store']),
            new Middleware('permission:pcbiaya-edit', only: ['edit', 'update', 'editt']),
            new Middleware('permission:pcbiaya-show', only: ['show']),
            new Middleware('permission:pcbiaya-delete', only: ['delete', 'destroy']),
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
        if (!$request->session()->exists('pcbiaya_pp')) {
            $request->session()->put('pcbiaya_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('pcbiaya_branch_id')) {
            $request->session()->put('pcbiaya_branch_id', 'all');
        }
        if (!$request->session()->exists('pcbiaya_tanggal')) {
            $request->session()->put('pcbiaya_tanggal', '_');
        }

        $search_arr = ['pcbiaya_branch_id', 'pcbiaya_tanggal'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = PcBiaya::join('branches', 'branches.id', '=', 'pc_pengeluarans.branch_id')
            ->join('users', 'users.id', '=', 'pc_pengeluarans.user_id')
            ->groupBy('pc_pengeluarans.tanggal', 'branches.nama', 'branches.id', 'users.name')
            ->orderBy('pc_pengeluarans.tanggal', 'desc')
            ->orderBy('branches.nama')
            ->selectRaw('pc_pengeluarans.tanggal, branches.id as branch_id, branches.nama as branch_nama, users.name as pc_nama, sum(pc_pengeluarans.harga) as total_biaya');

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pcbiaya_'));

            if ($search_arr[$i] == 'pcbiaya_branch_id') {
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
        $datas = $datas->paginate(session('pcbiaya_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('pcbiaya.index', compact(['datas', 'branches']))->with('i', (request()->input('page', 1) - 1) * session('pcbiaya_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('pcbiaya_pp', $request->pp);
        $request->session()->put('pcbiaya_branch_id', $request->branch);
        $request->session()->put('pcbiaya_tanggal', $request->tanggal);

        $search_arr = ['pcbiaya_branch_id', 'pcbiaya_tanggal'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = PcBiaya::join('branches', 'branches.id', '=', 'pc_pengeluarans.branch_id')
            ->join('users', 'users.id', '=', 'pc_pengeluarans.user_id')
            ->groupBy('pc_pengeluarans.tanggal', 'branches.nama', 'branches.id', 'users.name')
            ->orderBy('pc_pengeluarans.tanggal', 'desc')
            ->orderBy('branches.nama')
            ->selectRaw('pc_pengeluarans.tanggal, branches.id as branch_id, branches.nama as branch_nama, users.name as pc_nama, sum(pc_pengeluarans.harga) as total_biaya');

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pcbiaya_'));

            if ($search_arr[$i] == 'pcbiaya_branch_id') {
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
        $datas = $datas->paginate(session('pcbiaya_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        $datas->withPath('/finance/pcbiaya'); // pagination url to

        $view = view('pcbiaya.partials.table', compact(['datas', 'branches']))->with('i', (request()->input('page', 1) - 1) * session('pcbiaya_pp'))->render();

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

    public function edit(Request $request)
    {
        //
    }

    public function update(Request $request)
    {
        //
    }

    public function editt(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $details = PcBiaya::join('users', 'users.id', '=', 'pc_pengeluarans.user_id')
            ->join('jenis_pengeluaran_cabangs', 'jenis_pengeluaran_cabangs.id', '=', 'pc_pengeluarans.jenis_pengeluaran_cabang_id')
            ->where('pc_pengeluarans.branch_id', Crypt::decrypt($request->branch_id))
            ->where('pc_pengeluarans.tanggal', Crypt::decrypt($request->tanggal))
            ->select('pc_pengeluarans.*', 'users.name as pc_nama', 'jenis_pengeluaran_cabangs.nama as jenis_nama')
            ->get();

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('pcbiaya.edit', compact(['details']));
    }

    public function updatee(Request $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $ids = $request->input('detail_id');
        $approveds = $request->input('approved', []);
        $approved_fins = $request->input('approved_fin', []);
        $i = 0;

        foreach ($ids as $id) {
            $biaya = PcBiaya::where('id', $id)->update([
                'approved' => isset($approved_fins[$i]) ? 1 : 0,
                'approved_fin' => isset($approved_fins[$i]) ? 1 : 0,
                'updated_by' => auth()->user()->email,
            ]);

            $i++;
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return redirect()->back()->with('success', __('messages.successupdated'));
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
