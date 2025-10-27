<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Http\Requests\KecamatanRequest;
use App\Models\Propinsi;
use App\Models\Kabupaten;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class KecamatanController extends Controller implements HasMiddleware
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
        if (!$request->session()->exists('kecamatan_pp')) {
            $request->session()->put('kecamatan_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('kecamatan_isactive')) {
            $request->session()->put('kecamatan_isactive', 'all');
        }
        if (!$request->session()->exists('kecamatan_nama')) {
            $request->session()->put('kecamatan_nama', '_');
        }

        $search_arr = ['kecamatan_isactive', 'kecamatan_nama'];

        $datas = Kecamatan::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = 'kecamatans.' . substr($search_arr[$i], strlen('kecamatan_'));

            if ($search_arr[$i] == 'kecamatan_isactive') {
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

        $datas = $datas->select('kecamatans.*')
            ->join('propinsis', 'propinsis.id', 'kecamatans.propinsi_id')
            ->join('kabupatens', 'kabupatens.id', 'kecamatans.kabupaten_id')
            ->orderByRaw('propinsis.nama, kabupatens.nama');
        $datas = $datas->latest()->paginate(session('kecamatan_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('kecamatan.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('kecamatan_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('kecamatan_pp', $request->pp);
        $request->session()->put('kecamatan_isactive', $request->isactive);
        $request->session()->put('kecamatan_nama', $request->nama);

        $search_arr = ['kecamatan_isactive', 'kecamatan_nama'];

        $datas = Kecamatan::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = 'kecamatans.' . substr($search_arr[$i], strlen('kecamatan_'));

            if ($search_arr[$i] == 'kecamatan_isactive') {
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

        $datas = $datas->select('kecamatans.*')
            ->join('propinsis', 'propinsis.id', 'kecamatans.propinsi_id')
            ->join('kabupatens', 'kabupatens.id', 'kecamatans.kabupaten_id')
            ->orderByRaw('propinsis.nama, kabupatens.nama');
        $datas = $datas->latest()->paginate(session('kecamatan_pp'));

        $datas->withPath('/marketing/kecamatan'); // pagination url to

        $view = view('kecamatan.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('kecamatan_pp'))->render();

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

        return view('kecamatan.create', compact(['propinsis', 'kabupatens']));
    }

    public function store(KecamatanRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $kecamatan = Kecamatan::create([
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($kecamatan) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Kecamatan::find(Crypt::decrypt($request->kecamatan));

        return view('kecamatan.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Kecamatan::find(Crypt::decrypt($request->kecamatan));
        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->where('propinsi_id', $datas->propinsi_id)->orderBy('nama')->pluck('nama', 'id');

        return view('kecamatan.edit', compact(['datas', 'propinsis', 'kabupatens']));
    }

    public function update(KecamatanRequest $request): RedirectResponse
    {
        $kecamatan = Kecamatan::find(Crypt::decrypt($request->kecamatan));

        if ($request->validated()) {

            $kecamatan->update([
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
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
        $kecamatan = Kecamatan::find(Crypt::decrypt($request->kecamatan));

        $datas = $kecamatan;

        return view('kecamatan.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $kecamatan = Kecamatan::find(Crypt::decrypt($request->kecamatan));

        try {
            $kecamatan->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('kecamatan.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('kecamatan.index')->with('error', $e->getMessage());
        }

        return redirect()->route('kecamatan.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $kecamatan->nama);
    }

    public function dependDropKab(Request $request): JsonResponse
    {
        $pr = $request->pr;

        if ($pr == 'all') {
            $kabs = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('id', 'nama');
        } else {
            $kabs = Kabupaten::where('isactive', 1)->where('propinsi_id', $pr)->orderBy('nama')->pluck('id', 'nama');
        }

        return response()->json([
            'status' => 200,
            'kabs' => $kabs,
        ]);
    }

    public function dependDropKec(Request $request): JsonResponse
    {
        $pr = $request->pr;
        $kb = $request->kb;

        $kecs = Kecamatan::where('isactive', 1)->where('propinsi_id', $pr)->where('kabupaten_id', $kb)->orderBy('nama')->pluck('id', 'nama');

        return response()->json([
            'status' => 200,
            'kecs' => $kecs,
        ]);
    }
}
