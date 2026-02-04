<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Brandivjab;
use App\Models\Division;
use App\Models\Jabatan;
use App\Http\Requests\BrandivjabRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BrandivjabController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:brandivjab-list', only: ['index', 'fetch']),
            new Middleware('permission:brandivjab-create', only: ['create', 'store']),
            new Middleware('permission:brandivjab-edit', only: ['edit', 'update']),
            new Middleware('permission:brandivjab-show', only: ['show']),
            new Middleware('permission:brandivjab-delete', only: ['delete', 'destroy']),
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
        if (!$request->session()->exists('brandivjab_pp')) {
            $request->session()->put('brandivjab_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('brandivjab_isactive')) {
            $request->session()->put('brandivjab_isactive', 'all');
        }
        if (!$request->session()->exists('brandivjab_branch_id')) {
            $request->session()->put('brandivjab_branch_id', 'all');
        }
        if (!$request->session()->exists('brandivjab_division_id')) {
            $request->session()->put('brandivjab_division_id', 'all');
        }
        if (!$request->session()->exists('brandivjab_jabatan_id')) {
            $request->session()->put('brandivjab_jabatan_id', 'all');
        }

        $search_arr = ['brandivjab_isactive', 'brandivjab_branch_id', 'brandivjab_division_id', 'brandivjab_jabatan_id'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $divisions = Division::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $jabatans = Jabatan::where('isactive', 1)->orderBy('islevel')->orderBy('nama')->pluck('nama', 'id');
        $datas = Brandivjab::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('brandivjab_'));

            if ($search_arr[$i] == 'brandivjab_isactive' || $search_arr[$i] == 'brandivjab_branch_id' || $search_arr[$i] == 'brandivjab_division_id' || $search_arr[$i] == 'brandivjab_jabatan_id') {
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
        // $datas = $datas->latest()->paginate(session('brandivjab_pp'));
        $datas = $datas->orderBy('branch_id')->orderBy('jabatan_id')->paginate(session('brandivjab_pp'));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('brandivjab.index', compact(['datas', 'branches', 'divisions', 'jabatans']))->with('i', (request()->input('page', 1) - 1) * session('brandivjab_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('brandivjab_pp', $request->pp);
        $request->session()->put('brandivjab_isactive', $request->isactive);
        $request->session()->put('brandivjab_branch_id', $request->branch);
        $request->session()->put('brandivjab_division_id', $request->division);
        $request->session()->put('brandivjab_jabatan_id', $request->jabatan);

        $search_arr = ['brandivjab_isactive', 'brandivjab_branch_id', 'brandivjab_division_id', 'brandivjab_jabatan_id'];

        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $divisions = Division::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $jabatans = Jabatan::where('isactive', 1)->orderBy('islevel')->orderBy('nama')->pluck('nama', 'id');
        $datas = Brandivjab::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('brandivjab_'));

            if ($search_arr[$i] == 'brandivjab_isactive' || $search_arr[$i] == 'brandivjab_branch_id' || $search_arr[$i] == 'brandivjab_division_id' || $search_arr[$i] == 'brandivjab_jabatan_id') {
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
        // $datas = $datas->latest()->paginate(session('brandivjab_pp'));
        $datas = $datas->orderBy('branch_id')->orderBy('jabatan_id')->paginate(session('brandivjab_pp'));

        $datas->withPath('/general-affair/brandivjab'); // pagination url to

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        $view = view('brandivjab.partials.table', compact(['datas', 'branches', 'divisions', 'jabatans']))->with('i', (request()->input('page', 1) - 1) * session('brandivjab_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $divisions = Division::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $jabatans = Jabatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $atasans = Brandivjab::where('isactive', 1)->orderBy('jabatan_id')->get();

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('brandivjab.create', compact(['branches', 'divisions', 'jabatans', 'atasans']));
    }

    public function store(BrandivjabRequest $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        if ($request->validated()) {
            $brandivjab = Brandivjab::create([
                'branch_id' => $request->branch_id,
                'division_id' => $request->division_id,
                'jabatan_id' => $request->jabatan_id,
                'atasan_id' => $request->atasan_id,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            if ($brandivjab) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $brandivjab->jabatan->nama);
            }
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $datas = Brandivjab::find(Crypt::decrypt($request->brandivjab));
        $atasan = Brandivjab::find($datas->atasan_id);

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('brandivjab.show', compact(['datas', 'atasan']));
    }

    public function edit(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $datas = Brandivjab::find(Crypt::decrypt($request->brandivjab));
        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $divisions = Division::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $jabatans = Jabatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $atasans = Brandivjab::where('isactive', 1)->orderBy('jabatan_id')->get();

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('brandivjab.edit', compact(['datas', 'branches', 'divisions', 'jabatans', 'atasans']));
    }

    public function update(BrandivjabRequest $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $brandivjab = Brandivjab::find(Crypt::decrypt($request->brandivjab));

        if ($request->validated()) {

            $brandivjab->update([
                'branch_id' => $request->branch_id,
                'division_id' => $request->division_id,
                'jabatan_id' => $request->jabatan_id,
                'atasan_id' => $request->atasan_id,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $brandivjab->jabatan->nama);
        } else {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $datas = Brandivjab::find(Crypt::decrypt($request->brandivjab));

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return view('brandivjab.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (auth()->user()->profile->site == 'KP') $this->db_switch(2);

        $brandivjab = Brandivjab::find(Crypt::decrypt($request->brandivjab));

        try {
            $brandivjab->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('brandivjab.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('brandivjab.index')->with('error', $e->getMessage());
        }

        if (auth()->user()->profile->site == 'KP') $this->db_switch(1);

        return redirect()->route('brandivjab.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $brandivjab->nama);
    }
}
