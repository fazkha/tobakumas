<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Mitra;
use App\Models\MitraPermintaanIzin;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MitraizinController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:mitraizin-list', only: ['index', 'fetch']),
            new Middleware('permission:mitraizin-create', only: ['create', 'store']),
            new Middleware('permission:mitraizin-edit', only: ['edit', 'update']),
            new Middleware('permission:mitraizin-show', only: ['show']),
            new Middleware('permission:mitraizin-delete', only: ['delete', 'destroy']),
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
        if (!$request->session()->exists('mitraizin_pp')) {
            $request->session()->put('mitraizin_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('mitraizin_branch_id')) {
            $request->session()->put('mitraizin_branch_id', 'all');
        }
        if (!$request->session()->exists('mitraizin_mitra_id')) {
            $request->session()->put('mitraizin_mitra_id', 'all');
        }

        $search_arr = ['mitraizin_branch_id', 'mitraizin_mitra_id'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $mitras = Mitra::where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');
        $datas = MitraPermintaanIzin::join('branches', 'branches.id', '=', 'mitra_permintaan_izins.branch_id')
            ->join('mitras', 'mitras.id', '=', 'mitra_permintaan_izins.mitra_id')
            ->join('jenis_izin_pegawais', 'jenis_izin_pegawais.id', '=', 'mitra_permintaan_izins.jenis_izin_pegawai_id')
            ->select('mitra_permintaan_izins.*', 'branches.nama as branch_nama', 'mitras.nama_lengkap as mitra_nama', 'jenis_izin_pegawais.nama as jenis_nama');

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('mitraizin_'));

            if ($search_arr[$i] == 'mitraizin_branch_id' || $search_arr[$i] == 'mitraizin_mitra_id') {
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
        $datas = $datas->latest()->paginate(session('mitraizin_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('mitraizin.index', compact(['datas', 'branches', 'mitras']))->with('i', (request()->input('page', 1) - 1) * session('mitraizin_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('mitraizin_pp', $request->pp);
        $request->session()->put('mitraizin_branch_id', $request->branch);
        $request->session()->put('mitraizin_mitra_id', $request->mitra);

        $search_arr = ['mitraizin_branch_id', 'mitraizin_mitra_id'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $mitras = Mitra::where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');
        $datas = MitraPermintaanIzin::join('branches', 'branches.id', '=', 'mitra_permintaan_izins.branch_id')
            ->join('mitras', 'mitras.id', '=', 'mitra_permintaan_izins.mitra_id')
            ->join('jenis_izin_pegawais', 'jenis_izin_pegawais.id', '=', 'mitra_permintaan_izins.jenis_izin_pegawai_id')
            ->select('mitra_permintaan_izins.*', 'branches.nama as branch_nama', 'mitras.nama_lengkap as mitra_nama', 'jenis_izin_pegawais.nama as jenis_nama');

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('mitraizin_'));

            if ($search_arr[$i] == 'mitraizin_branch_id' || $search_arr[$i] == 'mitraizin_mitra_id') {
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
        $datas = $datas->latest()->paginate(session('mitraizin_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        $datas->withPath('/human-resource/mitraizin'); // pagination url to

        $view = view('mitraizin.partials.table', compact(['datas', 'branches', 'mitras']))->with('i', (request()->input('page', 1) - 1) * session('mitraizin_pp'))->render();

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

        $datas = MitraPermintaanIzin::join('branches', 'branches.id', '=', 'mitra_permintaan_izins.branch_id')
            ->join('mitras', 'mitras.id', '=', 'mitra_permintaan_izins.mitra_id')
            ->join('jenis_izin_pegawais', 'jenis_izin_pegawais.id', '=', 'mitra_permintaan_izins.jenis_izin_pegawai_id')
            ->select('mitra_permintaan_izins.*', 'branches.nama as branch_nama', 'mitras.nama_lengkap as mitra_nama', 'jenis_izin_pegawais.nama as jenis_nama')
            ->where('mitra_permintaan_izins.id', Crypt::decrypt($request->mitraizin))
            ->first();

        return view('mitraizin.edit', compact(['datas']));
    }

    public function update(Request $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $mitraizin = MitraPermintaanIzin::find(Crypt::decrypt($request->mitraizin));

        if ($mitraizin) {
            $mitraizin->update([
                'approved_hrd' => ($request->approved_hrd == 'on' ? 1 : 0),
            ]);

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->mitra->nama_lengkap);
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
