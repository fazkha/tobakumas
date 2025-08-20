<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandivjabRequest;
use App\Models\Branch;
use App\Models\Brandivjab;
use App\Models\Division;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

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

    public function index(Request $request)
    {
        if (!$request->session()->exists('brandivjab_pp')) {
            $request->session()->put('brandivjab_pp', 12);
        }
        if (!$request->session()->exists('brandivjab_isactive')) {
            $request->session()->put('brandivjab_isactive', 'all');
        }
        if (!$request->session()->exists('brandivjab_branch_id')) {
            $request->session()->put('brandivjab_branch_id', 'all');
        }

        $search_arr = ['brandivjab_isactive', 'brandivjab_branch_id'];

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Brandivjab::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('brandivjab_'));

            if ($search_arr[$i] == 'brandivjab_isactive' || $search_arr[$i] == 'brandivjab_branch_id') {
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
        $datas = $datas->latest()->paginate(session('brandivjab_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('brandivjab.index', compact(['datas', 'branches']))->with('i', (request()->input('page', 1) - 1) * session('brandivjab_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('brandivjab_pp', $request->pp);
        $request->session()->put('brandivjab_isactive', $request->isactive);
        $request->session()->put('brandivjab_branch_id', $request->branch);

        $search_arr = ['brandivjab_isactive', 'brandivjab_branch_id'];

        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Brandivjab::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('brandivjab_'));

            if ($search_arr[$i] == 'brandivjab_isactive' || $search_arr[$i] == 'brandivjab_branch_id') {
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
        $datas = $datas->latest()->paginate(session('brandivjab_pp'));

        $datas->withPath('/general-affair/brandivjab'); // pagination url to

        $view = view('brandivjab.partials.table', compact(['datas', 'branches']))->with('i', (request()->input('page', 1) - 1) * session('brandivjab_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $divisions = Division::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $jabatans = Jabatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $atasans = Brandivjab::where('isactive', 1)->get();

        return view('brandivjab.create', compact(['branches', 'divisions', 'jabatans', 'atasans']));
    }

    public function store(BrandivjabRequest $request): RedirectResponse
    {
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

            if ($brandivjab) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $brandivjab->jabatan->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Brandivjab::find(Crypt::decrypt($request->brandivjab));
        $atasan = Brandivjab::find($datas->atasan_id);

        return view('brandivjab.show', compact(['datas', 'atasan']));
    }

    public function edit(Request $request): View
    {
        $datas = Brandivjab::find(Crypt::decrypt($request->brandivjab));
        $branches = Branch::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $divisions = Division::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $jabatans = Jabatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $atasans = Brandivjab::where('isactive', 1)->get();

        return view('brandivjab.edit', compact(['datas', 'branches', 'divisions', 'jabatans', 'atasans']));
    }

    public function update(BrandivjabRequest $request): RedirectResponse
    {
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

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $brandivjab->jabatan->nama);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $datas = Brandivjab::find(Crypt::decrypt($request->brandivjab));

        return view('brandivjab.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $brandivjab = Brandivjab::find(Crypt::decrypt($request->brandivjab));

        try {
            $brandivjab->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('brandivjab.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('brandivjab.index')->with('error', $e->getMessage());
        }

        return redirect()->route('brandivjab.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $brandivjab->nama);
    }
}
