<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Branch;
use App\Http\Requests\GudangRequest;
use App\Http\Requests\GudangUpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class GudangController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:gudang-list', only: ['index', 'fetch']),
            new Middleware('permission:gudang-create', only: ['create', 'store']),
            new Middleware('permission:gudang-edit', only: ['edit', 'update']),
            new Middleware('permission:gudang-show', only: ['show']),
            new Middleware('permission:gudang-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('gudang_pp')) {
            $request->session()->put('gudang_pp', 15);
        }
        if (!$request->session()->exists('gudang_isactive')) {
            $request->session()->put('gudang_isactive', 'all');
        }
        if (!$request->session()->exists('gudang_kode')) {
            $request->session()->put('gudang_kode', '_');
        }
        if (!$request->session()->exists('gudang_nama')) {
            $request->session()->put('gudang_nama', '_');
        }
        if (!$request->session()->exists('gudang_alamat')) {
            $request->session()->put('gudang_alamat', '_');
        }

        $search_arr = ['gudang_isactive', 'gudang_kode', 'gudang_nama', 'gudang_alamat'];

        $datas = Gudang::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('gudang_'));

            if ($search_arr[$i] == 'gudang_isactive') {
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

        $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        $datas = $datas->latest()->paginate(session('gudang_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('gudang.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('gudang_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('gudang_pp', $request->pp);
        $request->session()->put('gudang_isactive', $request->isactive);
        $request->session()->put('gudang_kode', $request->kode);
        $request->session()->put('gudang_nama', $request->nama);
        $request->session()->put('gudang_alamat', $request->alamat);

        $search_arr = ['gudang_isactive', 'gudang_kode', 'gudang_nama', 'gudang_alamat'];

        $datas = Gudang::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('gudang_'));

            if ($search_arr[$i] == 'gudang_isactive') {
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

        $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        $datas = $datas->latest()->paginate(session('gudang_pp'));

        $datas->withPath('/warehouse/gudang'); // pagination url to

        $view = view('gudang.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('gudang_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $branch = Branch::find($branch_id);

        return view('gudang.create', compact('branch_id', 'branch'));
    }

    public function store(GudangRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $gudang = Gudang::create([
                'branch_id' => $request->branch_id,
                'kode' => $request->kode,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($gudang) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Gudang::find(Crypt::decrypt($request->gudang));

        return view('gudang.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Gudang::find(Crypt::decrypt($request->gudang));

        return view('gudang.edit', compact(['datas']));
    }

    public function update(GudangUpdateRequest $request): RedirectResponse
    {
        $gudang = Gudang::find(Crypt::decrypt($request->gudang));

        if ($request->validated()) {

            $gudang->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'keterangan' => $request->keterangan,
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
        $gudang = Gudang::find(Crypt::decrypt($request->gudang));

        $datas = $gudang;

        return view('gudang.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $gudang = Gudang::find(Crypt::decrypt($request->gudang));

        try {
            $gudang->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('gudang.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('gudang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('gudang.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $gudang->nama);
    }
}
