<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Http\Requests\KabupatenRequest;
use App\Models\Propinsi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class KabupatenController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:kabupaten-list', only: ['index', 'fetch']),
            new Middleware('permission:kabupaten-create', only: ['create', 'store']),
            new Middleware('permission:kabupaten-edit', only: ['edit', 'update']),
            new Middleware('permission:kabupaten-show', only: ['show']),
            new Middleware('permission:kabupaten-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('kabupaten_pp')) {
            $request->session()->put('kabupaten_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('kabupaten_isactive')) {
            $request->session()->put('kabupaten_isactive', 'all');
        }
        if (!$request->session()->exists('kabupaten_nama')) {
            $request->session()->put('kabupaten_nama', '_');
        }

        $search_arr = ['kabupaten_isactive', 'kabupaten_nama'];

        $datas = Kabupaten::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = 'kabupatens.' . substr($search_arr[$i], strlen('kabupaten_'));

            if ($search_arr[$i] == 'kabupaten_isactive') {
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

        $datas = $datas->select('kabupatens.*')
            ->join('propinsis', 'propinsis.id', 'kabupatens.propinsi_id')
            ->orderByRaw('propinsis.nama, kabupatens.nama');
        $datas = $datas->latest()->paginate(session('kabupaten_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('kabupaten.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('kabupaten_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('kabupaten_pp', $request->pp);
        $request->session()->put('kabupaten_isactive', $request->isactive);
        $request->session()->put('kabupaten_nama', $request->nama);

        $search_arr = ['kabupaten_isactive', 'kabupaten_nama'];

        $datas = Kabupaten::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = 'kabupatens.' . substr($search_arr[$i], strlen('kabupaten_'));

            if ($search_arr[$i] == 'kabupaten_isactive') {
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

        $datas = $datas->select('kabupatens.*')
            ->join('propinsis', 'propinsis.id', 'kabupatens.propinsi_id')
            ->orderByRaw('propinsis.nama, kabupatens.nama');
        $datas = $datas->latest()->paginate(session('kabupaten_pp'));

        $datas->withPath('/marketing/kabupaten'); // pagination url to

        $view = view('kabupaten.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('kabupaten_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        return view('kabupaten.create', compact(['propinsis']));
    }

    public function store(KabupatenRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $kabupaten = Kabupaten::create([
                'propinsi_id' => $request->propinsi_id,
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($kabupaten) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Kabupaten::find(Crypt::decrypt($request->kabupaten));

        return view('kabupaten.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Kabupaten::find(Crypt::decrypt($request->kabupaten));
        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        return view('kabupaten.edit', compact(['datas', 'propinsis']));
    }

    public function update(KabupatenRequest $request): RedirectResponse
    {
        $kabupaten = Kabupaten::find(Crypt::decrypt($request->kabupaten));

        if ($request->validated()) {

            $kabupaten->update([
                'propinsi_id' => $request->propinsi_id,
                'nama' => $request->nama,
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
        $kabupaten = Kabupaten::find(Crypt::decrypt($request->kabupaten));

        $datas = $kabupaten;

        return view('kabupaten.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $kabupaten = Kabupaten::find(Crypt::decrypt($request->kabupaten));

        try {
            $kabupaten->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('kabupaten.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('kabupaten.index')->with('error', $e->getMessage());
        }

        return redirect()->route('kabupaten.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $kabupaten->nama);
    }
}
