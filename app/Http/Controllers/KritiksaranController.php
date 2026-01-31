<?php

namespace App\Http\Controllers;

use App\Models\MitraKritikSaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class KritiksaranController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:kritiksaran-list', only: ['index', 'fetch']),
            new Middleware('permission:kritiksaran-create', only: ['create', 'store']),
            new Middleware('permission:kritiksaran-edit', only: ['edit', 'update']),
            new Middleware('permission:kritiksaran-show', only: ['show']),
            new Middleware('permission:kritiksaran-delete', only: ['delete', 'destroy']),
        ];
    }

    public function db_switch($sw)
    {
        if ($sw == 2) {
            Config::set('database.connections.mysql.database', 'tobakuma_02');
            Config::set('database.connections.mysql.username', 'tobakuma_dbadmin');
            Config::set('database.connections.mysql.password', 'SaA(o-6y55a0TQ');
        } elseif ($sw == 1) {
            Config::set('database.connections.mysql.database', 'tobakuma_01');
            Config::set('database.connections.mysql.username', 'tobakuma_dbadmin');
            Config::set('database.connections.mysql.password', 'SaA(o-6y55a0TQ');
        }

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('kritiksaran_pp')) {
            $request->session()->put('kritiksaran_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('kritiksaran_isactive')) {
            $request->session()->put('kritiksaran_isactive', 'all');
        }
        if (!$request->session()->exists('kritiksaran_judul')) {
            $request->session()->put('kritiksaran_judul', '_');
        }
        if (!$request->session()->exists('kritiksaran_keterangan')) {
            $request->session()->put('kritiksaran_keterangan', '_');
        }

        $search_arr = ['kritiksaran_isactive', 'kritiksaran_judul', 'kritiksaran_keterangan'];

        $this->db_switch(2);
        $datas = MitraKritikSaran::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('kritiksaran_'));

            if ($search_arr[$i] == 'kritiksaran_isactive') {
                if (session($search_arr[$i]) !== 'all') {
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

        // $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        // $datas = $datas->orderBy('jenis_barang_id')->orderBy('nama')->paginate(session('barang_pp'));
        $datas = $datas->latest()->paginate(session('kritiksaran_pp'));
        $this->db_switch(1);

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('kritiksaran.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('kritiksaran_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('kritiksaran_pp', $request->pp);
        $request->session()->put('kritiksaran_isactive', $request->isactive);
        $request->session()->put('kritiksaran_judul', $request->judul);
        $request->session()->put('kritiksaran_keterangan', $request->keterangan);

        $search_arr = ['kritiksaran_isactive', 'kritiksaran_judul', 'kritiksaran_keterangan'];

        $datas = MitraKritikSaran::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('kritiksaran_'));

            if ($search_arr[$i] == 'kritiksaran_isactive') {
                if (session($search_arr[$i]) !== 'all') {
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

        // $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        // $datas = $datas->orderBy('jenis_barang_id')->orderBy('nama')->paginate(session('barang_pp'));
        $datas = $datas->latest()->paginate(session('kritiksaran_pp'));

        $datas->withPath('/human-resource/criticism'); // pagination url to

        $view = view('kritiksaran.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('kritiksaran_pp'))->render();

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

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
