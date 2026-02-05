<?php

namespace App\Http\Controllers;

use App\Http\Requests\KritiksaranRequest;
use App\Models\MitraKritikSaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

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
        $datas = $datas->orderBy('tanggal', 'desc')->paginate(session('kritiksaran_pp'));
        // $datas = $datas->latest()->paginate(session('kritiksaran_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

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

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

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
        $datas = $datas->orderBy('tanggal', 'desc')->paginate(session('kritiksaran_pp'));
        // $datas = $datas->latest()->paginate(session('kritiksaran_pp'));

        $datas->withPath('/human-resource/criticism'); // pagination url to

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

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

    public function show(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $datas = MitraKritikSaran::find(Crypt::decrypt($request->criticism));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('kritiksaran.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branch_id = auth()->user()->profile->branch_id;
        $datas = MitraKritikSaran::find(Crypt::decrypt($request->criticism));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('kritiksaran.edit', compact(['datas', 'branch_id']));
    }

    public function update(KritiksaranRequest $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $kritiksaran = MitraKritikSaran::find(Crypt::decrypt($request->criticism));

        if ($request->validated()) {
            $kritiksaran->update([
                'tanggal' => $request->tanggal,
                'judul' => ucfirst($request->judul),
                'keterangan' => ucfirst($request->keterangan),
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'tanggal_jawab' => date("Y-m-d"),
                'keterangan_jawab' => $request->keterangan_jawab,
            ]);

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->judul);
        } else {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $kritiksaran = MitraKritikSaran::find(Crypt::decrypt($request->criticism));

        $datas = $kritiksaran;

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('kritiksaran.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $kritiksaran = MitraKritikSaran::find(Crypt::decrypt($request->criticism));

        try {
            $kritiksaran->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('criticism.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('criticism.index')->with('error', $e->getMessage());
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return redirect()->route('criticism.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $kritiksaran->judul);
    }
}
