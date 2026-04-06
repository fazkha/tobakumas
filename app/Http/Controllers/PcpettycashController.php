<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Kabupaten;
use App\Models\Propinsi;
use App\Http\Requests\BranchRequest;
use App\Models\Kecamatan;
use App\Models\PcPettyCash;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PcpettycashController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:pcpettycash-list', only: ['index', 'fetch']),
            new Middleware('permission:pcpettycash-create', only: ['create', 'store']),
            new Middleware('permission:pcpettycash-edit', only: ['edit', 'update']),
            new Middleware('permission:pcpettycash-show', only: ['show']),
            new Middleware('permission:pcpettycash-delete', only: ['delete', 'destroy']),
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
        if (!$request->session()->exists('pcpettycash_pp')) {
            $request->session()->put('pcpettycash_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('pcpettycash_branch_id')) {
            $request->session()->put('pcpettycash_branch_id', 'all');
        }
        if (!$request->session()->exists('pcpettycash_tanggal')) {
            $request->session()->put('pcpettycash_tanggal', '_');
        }

        $search_arr = ['pcpettycash_branch_id', 'pcpettycash_tanggal'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = PcPettyCash::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pcpettycash_'));

            if ($search_arr[$i] == 'pcpettycash_branch_id') {
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
        $datas = $datas->orderBy('tanggal', 'desc')->latest()->paginate(session('pcpettycash_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('pcpettycash.index', compact(['datas', 'branches']))->with('i', (request()->input('page', 1) - 1) * session('pcpettycash_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('pcpettycash_pp', $request->pp);
        $request->session()->put('pcpettycash_branch_id', $request->branch);
        $request->session()->put('pcpettycash_tanggal', $request->tanggal);

        $search_arr = ['pcpettycash_branch_id', 'pcpettycash_tanggal'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = PcPettyCash::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('pcpettycash_'));

            if ($search_arr[$i] == 'pcpettycash_branch_id') {
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
        $datas = $datas->orderBy('tanggal', 'desc')->latest()->paginate(session('pcpettycash_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        $datas->withPath('/finance/pcpettycash'); // pagination url to

        $view = view('pcpettycash.partials.table', compact(['datas', 'branches']))->with('i', (request()->input('page', 1) - 1) * session('pcpettycash_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        return view('branch.create', compact('propinsis', 'kabupatens', 'kecamatans'));
    }

    public function store(BranchRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $branch = Branch::create([
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kode' => $request->kode,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'kodepos' => $request->kodepos,
                'keterangan' => $request->keterangan,
                'email' => $request->email,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($branch) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Branch::find(Crypt::decrypt($request->branch));

        return view('branch.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Branch::find(Crypt::decrypt($request->branch));
        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->where('propinsi_id', $datas->propinsi_id)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->where('kabupaten_id', $datas->kabupaten_id)->orderBy('nama')->pluck('nama', 'id');

        $syntax = 'CALL sp_mitra_cabang(' . Crypt::decrypt($request->branch) . ')';
        $pcmitra = DB::select($syntax);

        $initialMarkers = [
            [
                'position' => [
                    'lat' => $datas->latitude ? $datas->latitude : config('custom.latitude'),
                    'lng' => $datas->longitude ? $datas->longitude : config('custom.longitude'),
                ],
                'title' => $datas->nama,
                'draggable' => $datas->latitude ? false : true
            ],
        ];
        // dd($initialMarkers);

        return view('branch.edit', compact(['datas', 'propinsis', 'kabupatens', 'kecamatans', 'pcmitra', 'initialMarkers']));
    }

    public function update(BranchRequest $request): RedirectResponse
    {
        $branch = Branch::find(Crypt::decrypt($request->branch));

        if ($request->validated()) {

            $branch->update([
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kode' => $request->kode,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'kodepos' => $request->kodepos,
                'keterangan' => $request->keterangan,
                'email' => $request->email,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->nama);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $branch = Branch::find(Crypt::decrypt($request->id));

        $datas = $branch;

        return view('pcpettycash.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $branch = Branch::find(Crypt::decrypt($request->branch));

        try {
            $branch->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('branch.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('branch.index')->with('error', $e->getMessage());
        }

        return redirect()->route('branch.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $branch->nama);
    }

    public function getAttribute(Request $request): JsonResponse
    {
        $get = Branch::find($request->id);

        if ($get) {
            $kode = $get->kode;
            $nama = $get->nama;
            $alamat = $get->alamat;
            $propinsi_id = $get->propinsi_id;
            $kabupaten_id = $get->kabupaten_id;
            $kecamatan_id = $get->kecamatan_id;

            return response()->json([
                'kode' => $kode,
                'nama' => $nama,
                'alamat' => $alamat,
                'propinsi_id' => $propinsi_id,
                'kabupaten_id' => $kabupaten_id,
                'kecamatan_id' => $kecamatan_id,
            ], 200);
        }

        return response()->json([
            'kode' => '-',
            'nama' => '-',
            'alamat' => '-',
            'propinsi_id' => null,
            'kabupaten_id' => null,
            'kecamatan_id' => null,
        ], 200);
    }
}
