<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Kabupaten;
use App\Models\Propinsi;
use App\Http\Requests\BranchRequest;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class BranchController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:branch-list', only: ['index', 'fetch']),
            new Middleware('permission:branch-create', only: ['create', 'store']),
            new Middleware('permission:branch-edit', only: ['edit', 'update']),
            new Middleware('permission:branch-show', only: ['show']),
            new Middleware('permission:branch-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('branch_pp')) {
            $request->session()->put('branch_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('branch_isactive')) {
            $request->session()->put('branch_isactive', 'all');
        }
        if (!$request->session()->exists('branch_nama')) {
            $request->session()->put('branch_nama', '_');
        }
        if (!$request->session()->exists('branch_alamat')) {
            $request->session()->put('branch_alamat', '_');
        }

        $search_arr = ['branch_isactive', 'branch_nama', 'branch_alamat'];

        $datas = Branch::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('branch_'));

            if ($search_arr[$i] == 'branch_isactive') {
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
        $datas = $datas->latest()->paginate(session('branch_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('branch.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('branch_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('branch_pp', $request->pp);
        $request->session()->put('branch_isactive', $request->isactive);
        $request->session()->put('branch_nama', $request->nama);
        $request->session()->put('branch_alamat', $request->alamat);

        $search_arr = ['branch_isactive', 'branch_nama', 'branch_alamat'];

        $datas = Branch::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('branch_'));

            if ($search_arr[$i] == 'branch_isactive') {
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
        $datas = $datas->latest()->paginate(session('branch_pp'));

        $datas->withPath('/general-affair/branch'); // pagination url to

        $view = view('branch.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('branch_pp'))->render();

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
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        return view('branch.edit', compact(['datas', 'propinsis', 'kabupatens', 'kecamatans']));
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
        $branch = Branch::find(Crypt::decrypt($request->branch));

        $datas = $branch;

        return view('branch.delete', compact(['datas']));
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
