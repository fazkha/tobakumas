<?php

namespace App\Http\Controllers;

use App\Models\Brandivjab;
use App\Models\Brandivjabkab;
use App\Models\Kabupaten;
use App\Models\Propinsi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class BrandivjabkabController extends Controller implements HasMiddleware
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
        if (!$request->session()->exists('brandivjabkab_pp')) {
            $request->session()->put('brandivjabkab_pp', 12);
        }
        if (!$request->session()->exists('brandivjabkab_isactive')) {
            $request->session()->put('brandivjabkab_isactive', 'all');
        }
        if (!$request->session()->exists('brandivjabkab_propinsi_id')) {
            $request->session()->put('brandivjabkab_propinsi_id', 'all');
        }
        if (!$request->session()->exists('brandivjabkab_kabupaten_id')) {
            $request->session()->put('brandivjabkab_kabupaten_id', 'all');
        }

        $search_arr = ['brandivjabkab_isactive', 'brandivjabkab_propinsi_id', 'brandivjabkab_kabupaten_id'];

        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Brandivjabkab::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('brandivjabkab_'));

            if ($search_arr[$i] == 'brandivjabkab_isactive' || $search_arr[$i] == 'brandivjabkab_propinsi_id' || $search_arr[$i] == 'brandivjabkab_kabupaten_id') {
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
        // $datas = $datas->latest()->paginate(session('brandivjabkab_pp'));
        $datas = $datas->selectRaw('brandivjab_id, keterangan, isactive')
            ->orderBy('brandivjab_id')
            ->orderBy('keterangan')
            ->groupBy('brandivjab_id')
            ->groupBy('keterangan')
            ->groupBy('isactive');
        $datas = $datas->paginate(session('brandivjabkab_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('brandivjabkab.index', compact(['datas', 'propinsis', 'kabupatens']))->with('i', (request()->input('page', 1) - 1) * session('brandivjabkab_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('brandivjabkab_pp', $request->pp);
        $request->session()->put('brandivjabkab_isactive', $request->isactive);
        $request->session()->put('brandivjabkab_propinsi_id', $request->propinsi);
        $request->session()->put('brandivjabkab_kabupaten_id', $request->kabupaten);

        $search_arr = ['brandivjabkab_isactive', 'brandivjabkab_propinsi_id', 'brandivjabkab_kabupaten_id'];

        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Brandivjabkab::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('brandivjabkab_'));

            if ($search_arr[$i] == 'brandivjabkab_isactive' || $search_arr[$i] == 'brandivjabkab_propinsi_id' || $search_arr[$i] == 'brandivjabkab_kabupaten_id') {
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
        $datas = $datas->selectRaw('brandivjab_id, keterangan, isactive')
            ->orderBy('brandivjab_id')
            ->orderBy('keterangan')
            ->groupBy('brandivjab_id')
            ->groupBy('keterangan')
            ->groupBy('isactive');
        $datas = $datas->paginate(session('brandivjabkab_pp'));

        $datas->withPath('/marketing/brandivjabkab'); // pagination url to

        $view = view('brandivjabkab.partials.table', compact(['datas', 'propinsis', 'kabupatens']))->with('i', (request()->input('page', 1) - 1) * session('brandivjabkab_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $propinsis = Propinsi::where('isactive', 1)->orderBy('id')->get();
        $kabupatens = Kabupaten::join('propinsis', 'propinsis.id', 'kabupatens.propinsi_id')
            ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as nama, kabupatens.id as id')
            ->where('kabupatens.isactive', 1)->orderBy('kabupatens.propinsi_id')->orderBy('kabupatens.nama')->get();
        $brandivjabs = Brandivjab::join('branches', 'branches.id', 'brandivjabs.branch_id')
            ->join('jabatans', 'jabatans.id', 'brandivjabs.jabatan_id')
            ->leftJoin('divisions', 'divisions.id', 'brandivjabs.division_id')
            ->selectRaw('concat(jabatans.nama,if(brandivjabs.keterangan is null,\'\',concat(\' \',brandivjabs.keterangan)),if(division_id is null, \'\', concat(\' \', divisions.nama)),\' \',branches.nama) as nama, brandivjabs.id as id')
            ->where('brandivjabs.isactive', 1)
            ->whereRaw('jabatans.islevel in (4,5)')
            ->orderBy('jabatans.islevel')
            ->pluck('nama', 'id');

        return view('brandivjabkab.create', compact(['propinsis', 'kabupatens', 'brandivjabs']));
    }

    public function store(Request $request): RedirectResponse
    {
        $kabs = $request->input('kabs');

        if ($kabs) {
            foreach ($kabs as $kab) {
                $prop = Kabupaten::find($kab);
                $brandivjabkab = Brandivjabkab::create([
                    'brandivjab_id' => $request->brandivjab_id,
                    'propinsi_id' => $prop->propinsi_id,
                    'kabupaten_id' => $kab,
                    'keterangan' => $request->keterangan,
                    'isactive' => ($request->isactive == 'on' ? 1 : 0),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }

            if ($brandivjabkab) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $brandivjabkab->keterangan);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request)
    {
        $data1 = Brandivjabkab::where('brandivjab_id', Crypt::decrypt($request->brandivjabkab))->first();

        if ($data1) {
            $datas =  Brandivjabkab::where('brandivjab_id', $data1->brandivjab_id)
                ->where('isactive', 1)
                ->orderBy('propinsi_id')
                ->orderBy('kabupaten_id')
                ->get();
            $propinsis = Propinsi::where('isactive', 1)->orderBy('id')->get();
            $kabupatens = Kabupaten::join('propinsis', 'propinsis.id', 'kabupatens.propinsi_id')
                ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as nama, kabupatens.id as id')
                ->where('kabupatens.isactive', 1)
                ->orderBy('kabupatens.propinsi_id')
                ->orderBy('kabupatens.id')
                ->get();
            $brandivjabs = Brandivjab::join('branches', 'branches.id', 'brandivjabs.branch_id')
                ->join('jabatans', 'jabatans.id', 'brandivjabs.jabatan_id')
                ->leftJoin('divisions', 'divisions.id', 'brandivjabs.division_id')
                ->selectRaw('concat(jabatans.nama,if(brandivjabs.keterangan is null,\'\',concat(\' \',brandivjabs.keterangan)),if(division_id is null, \'\', concat(\' \', divisions.nama)),\' \',branches.nama) as nama, brandivjabs.id as id')
                ->where('brandivjabs.id', $data1->brandivjab_id)
                ->first();

            return view('brandivjabkab.show', compact(['datas', 'propinsis', 'kabupatens', 'brandivjabs']));
        }

        return redirect()->back();
    }

    public function edit(Request $request)
    {
        $data1 = Brandivjabkab::where('brandivjab_id', Crypt::decrypt($request->brandivjabkab))->first();

        if ($data1) {
            $datas =  Brandivjabkab::where('brandivjab_id', $data1->brandivjab_id)
                ->where('isactive', 1)
                ->orderBy('propinsi_id')
                ->orderBy('kabupaten_id')
                ->get();
            $propinsis = Propinsi::where('isactive', 1)->orderBy('id')->get();
            $kabupatens = Kabupaten::join('propinsis', 'propinsis.id', 'kabupatens.propinsi_id')
                ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as nama, kabupatens.id as id')
                ->where('kabupatens.isactive', 1)
                ->orderBy('kabupatens.propinsi_id')
                ->orderBy('kabupatens.id')
                ->get();
            $brandivjabs = Brandivjab::join('branches', 'branches.id', 'brandivjabs.branch_id')
                ->join('jabatans', 'jabatans.id', 'brandivjabs.jabatan_id')
                ->leftJoin('divisions', 'divisions.id', 'brandivjabs.division_id')
                ->selectRaw('concat(jabatans.nama,if(brandivjabs.keterangan is null,\'\',concat(\' \',brandivjabs.keterangan)),if(division_id is null, \'\', concat(\' \', divisions.nama)),\' \',branches.nama) as nama, brandivjabs.id as id')
                ->where('brandivjabs.isactive', 1)
                ->whereRaw('jabatans.islevel in (4,5)')
                ->orderBy('jabatans.islevel')
                ->pluck('nama', 'id');

            return view('brandivjabkab.edit', compact(['datas', 'propinsis', 'kabupatens', 'brandivjabs']));
        }

        return redirect()->back();
    }

    public function update(Request $request): RedirectResponse
    {
        $brandivjabkab = Brandivjabkab::where('brandivjab_id', Crypt::decrypt($request->brandivjabkab));
        $kabs = $request->input('kabs');
        // dd($brandivjabkab);
        // dd($kabs);

        if ($kabs) {
            $brandivjabkab->delete();

            foreach ($kabs as $kab) {
                $prop = Kabupaten::find($kab);
                $brandivjabkab = Brandivjabkab::create([
                    'brandivjab_id' => $request->brandivjab_id,
                    'propinsi_id' => $prop->propinsi_id,
                    'kabupaten_id' => $kab,
                    'keterangan' => $request->keterangan,
                    'isactive' => ($request->isactive == 'on' ? 1 : 0),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }

            if ($brandivjabkab) {
                return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $brandivjabkab->brandivjab->jabatan->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function updateDetail(Request $request): RedirectResponse
    {
        $brandivjabkab = Brandivjabkab::where('brandivjab_id', Crypt::decrypt($request->brandivjabkab));
        $kabs = $request->input('kabs');
        // dd($brandivjabkab);
        // dd($kabs);

        if ($kabs) {
            $brandivjabkab->delete();

            foreach ($kabs as $kab) {
                $prop = Kabupaten::find($kab);
                $brandivjabkab = Brandivjabkab::create([
                    'brandivjab_id' => $request->brandivjab_id,
                    'propinsi_id' => $prop->propinsi_id,
                    'kabupaten_id' => $kab,
                    'keterangan' => $request->keterangan,
                    'isactive' => ($request->isactive == 'on' ? 1 : 0),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }

            if ($brandivjabkab) {
                return redirect()->route('brandivjabkab.edit', Crypt::encrypt($brandivjabkab->id))->with('success', __('messages.successupdated') . ' 👉 ' . $brandivjabkab->brandivjab->jabatan->nama . ' ' . $brandivjabkab->brandivjab->keterangan);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function delete(Request $request): View
    {
        $datas = Brandivjabkab::find(Crypt::decrypt($request->brandivjabkab));

        return view('brandivjabkab.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $brandivjabkab = Brandivjabkab::find(Crypt::decrypt($request->brandivjabkab));

        try {
            $brandivjabkab->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('brandivjabkab.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('brandivjabkab.index')->with('error', $e->getMessage());
        }

        return redirect()->route('brandivjabkab.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $brandivjabkab->nama);
    }
}
