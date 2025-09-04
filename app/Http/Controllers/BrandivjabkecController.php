<?php

namespace App\Http\Controllers;

use App\Models\Brandivjab;
use App\Models\Brandivjabkec;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Propinsi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class BrandivjabkecController extends Controller implements HasMiddleware
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
        if (!$request->session()->exists('brandivjabkec_pp')) {
            $request->session()->put('brandivjabkec_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('brandivjabkec_isactive')) {
            $request->session()->put('brandivjabkec_isactive', 'all');
        }
        if (!$request->session()->exists('brandivjabkec_propinsi_id')) {
            $request->session()->put('brandivjabkec_propinsi_id', 'all');
        }
        if (!$request->session()->exists('brandivjabkec_kabupaten_id')) {
            $request->session()->put('brandivjabkec_kabupaten_id', 'all');
        }

        $search_arr = ['brandivjabkec_isactive', 'brandivjabkec_propinsi_id', 'brandivjabkec_kabupaten_id'];

        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Brandivjabkec::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('brandivjabkec_'));

            if ($search_arr[$i] == 'brandivjabkec_isactive' || $search_arr[$i] == 'brandivjabkec_propinsi_id' || $search_arr[$i] == 'brandivjabkec_kabupaten_id') {
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
        // $datas = $datas->latest()->paginate(session('brandivjabkec_pp'));
        $datas = $datas->selectRaw('brandivjab_id, keterangan, isactive')
            ->orderBy('brandivjab_id')
            ->orderBy('keterangan')
            ->groupBy('brandivjab_id')
            ->groupBy('keterangan')
            ->groupBy('isactive');
        $datas = $datas->paginate(session('brandivjabkec_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('brandivjabkec.index', compact(['datas', 'propinsis', 'kabupatens', 'kecamatans']))->with('i', (request()->input('page', 1) - 1) * session('brandivjabkec_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('brandivjabkec_pp', $request->pp);
        $request->session()->put('brandivjabkec_isactive', $request->isactive);
        $request->session()->put('brandivjabkec_propinsi_id', $request->propinsi);
        $request->session()->put('brandivjabkec_kabupaten_id', $request->kabupaten);

        $search_arr = ['brandivjabkec_isactive', 'brandivjabkec_propinsi_id', 'brandivjabkec_kabupaten_id'];

        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = Brandivjabkec::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('brandivjabkec_'));

            if ($search_arr[$i] == 'brandivjabkec_isactive' || $search_arr[$i] == 'brandivjabkec_propinsi_id' || $search_arr[$i] == 'brandivjabkec_kabupaten_id') {
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
        $datas = $datas->paginate(session('brandivjabkec_pp'));

        $datas->withPath('/marketing/brandivjabkec'); // pagination url to

        $view = view('brandivjabkec.partials.table', compact(['datas', 'propinsis', 'kabupatens']))->with('i', (request()->input('page', 1) - 1) * session('brandivjabkec_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $kecamatans = Kecamatan::join('kabupatens', 'kabupatens.id', 'kecamatans.kabupaten_id')
            ->join('propinsis', 'propinsis.id', 'kabupatens.propinsi_id')
            ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as namakabupaten, kecamatans.nama as nama, kecamatans.id as id')
            ->where('kecamatans.isactive', 1)->where('kabupatens.isactive', 1)->where('propinsis.isactive', 1)
            ->orderBy('kecamatans.propinsi_id')->orderBy('kecamatans.kabupaten_id')->orderBy('kecamatans.nama')->get();
        $brandivjabs = Brandivjab::join('branches', 'branches.id', 'brandivjabs.branch_id')
            ->join('jabatans', 'jabatans.id', 'brandivjabs.jabatan_id')
            ->leftJoin('divisions', 'divisions.id', 'brandivjabs.division_id')
            ->selectRaw('concat(jabatans.nama,if(brandivjabs.keterangan is null,\'\',concat(\' \',brandivjabs.keterangan)),if(division_id is null, \'\', concat(\' \', divisions.nama)),\' \',branches.nama) as nama, brandivjabs.id as id')
            ->where('brandivjabs.isactive', 1)
            ->whereRaw('jabatans.islevel in (4,5)')
            ->orderBy('jabatans.islevel')
            ->pluck('nama', 'id');

        return view('brandivjabkec.create', compact(['kecamatans', 'brandivjabs']));
    }

    public function store(Request $request): RedirectResponse
    {
        $kecs = $request->input('kecs');

        if ($kecs) {
            foreach ($kecs as $kec) {
                $prop = Kecamatan::find($kec);
                $brandivjabkec = Brandivjabkec::create([
                    'brandivjab_id' => $request->brandivjab_id,
                    'propinsi_id' => $prop->propinsi_id,
                    'kabupaten_id' => $prop->kabupaten_id,
                    'kecamatan_id' => $kec,
                    'keterangan' => $request->keterangan,
                    'isactive' => ($request->isactive == 'on' ? 1 : 0),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }

            if ($brandivjabkec) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $brandivjabkec->keterangan);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request)
    {
        $data1 = Brandivjabkec::where('brandivjab_id', Crypt::decrypt($request->brandivjabkec))->first();

        if ($data1) {
            $datas =  Brandivjabkec::where('brandivjab_id', $data1->brandivjab_id)
                ->where('isactive', 1)
                ->orderBy('propinsi_id')
                ->orderBy('kabupaten_id')
                ->orderBy('kecamatan_id')
                ->get();
            $kecamatans = Kecamatan::join('kabupatens', 'kabupatens.id', 'kecamatans.kabupaten_id')
                ->join('propinsis', 'propinsis.id', 'kabupatens.propinsi_id')
                ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as namakabupaten, kecamatans.nama as nama, kecamatans.id as id')
                ->where('kecamatans.isactive', 1)->where('kabupatens.isactive', 1)->where('propinsis.isactive', 1)
                ->orderBy('kecamatans.propinsi_id')->orderBy('kecamatans.kabupaten_id')->orderBy('kecamatans.nama')->get();
            $brandivjabs = Brandivjab::join('branches', 'branches.id', 'brandivjabs.branch_id')
                ->join('jabatans', 'jabatans.id', 'brandivjabs.jabatan_id')
                ->leftJoin('divisions', 'divisions.id', 'brandivjabs.division_id')
                ->selectRaw('concat(jabatans.nama,if(brandivjabs.keterangan is null,\'\',concat(\' \',brandivjabs.keterangan)),if(division_id is null, \'\', concat(\' \', divisions.nama)),\' \',branches.nama) as nama, brandivjabs.id as id')
                ->where('brandivjabs.id', $data1->brandivjab_id)
                ->first();

            return view('brandivjabkec.show', compact(['datas', 'kecamatans', 'brandivjabs']));
        }

        return redirect()->back();
    }

    public function edit(Request $request)
    {
        $data1 = Brandivjabkec::where('brandivjab_id', Crypt::decrypt($request->brandivjabkec))->first();

        if ($data1) {
            $datas =  Brandivjabkec::where('brandivjab_id', $data1->brandivjab_id)
                ->where('isactive', 1)
                ->orderBy('propinsi_id')
                ->orderBy('kabupaten_id')
                ->orderBy('kecamatan_id')
                ->get();
            $kecamatans = Kecamatan::join('kabupatens', 'kabupatens.id', 'kecamatans.kabupaten_id')
                ->join('propinsis', 'propinsis.id', 'kabupatens.propinsi_id')
                ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as namakabupaten, kecamatans.nama as nama, kecamatans.id as id')
                ->where('kecamatans.isactive', 1)->where('kabupatens.isactive', 1)->where('propinsis.isactive', 1)
                ->orderBy('kecamatans.propinsi_id')->orderBy('kecamatans.kabupaten_id')->orderBy('kecamatans.nama')->get();
            $brandivjabs = Brandivjab::join('branches', 'branches.id', 'brandivjabs.branch_id')
                ->join('jabatans', 'jabatans.id', 'brandivjabs.jabatan_id')
                ->leftJoin('divisions', 'divisions.id', 'brandivjabs.division_id')
                ->selectRaw('concat(jabatans.nama,if(brandivjabs.keterangan is null,\'\',concat(\' \',brandivjabs.keterangan)),if(division_id is null, \'\', concat(\' \', divisions.nama)),\' \',branches.nama) as nama, brandivjabs.id as id')
                ->where('brandivjabs.isactive', 1)
                ->whereRaw('jabatans.islevel in (4,5)')
                ->orderBy('jabatans.islevel')
                ->pluck('nama', 'id');

            return view('brandivjabkec.edit', compact(['datas', 'kecamatans', 'brandivjabs']));
        }

        return redirect()->back();
    }

    public function update(Request $request): RedirectResponse
    {
        $brandivjabkec = Brandivjabkec::where('brandivjab_id', Crypt::decrypt($request->brandivjabkec));
        $kecs = $request->input('kecs');
        // dd($brandivjabkec);
        // dd($kabs);

        if ($kecs) {
            $brandivjabkec->delete();

            foreach ($kecs as $kec) {
                $prop = Kecamatan::find($kec);
                $brandivjabkec = Brandivjabkec::create([
                    'brandivjab_id' => $request->brandivjab_id,
                    'propinsi_id' => $prop->propinsi_id,
                    'kabupaten_id' => $prop->kabupaten_id,
                    'kecamatan_id' => $kec,
                    'keterangan' => $request->keterangan,
                    'isactive' => ($request->isactive == 'on' ? 1 : 0),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }

            if ($brandivjabkec) {
                return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $brandivjabkec->brandivjab->jabatan->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function updateDetail(Request $request): RedirectResponse
    {
        $brandivjabkec = Brandivjabkec::where('brandivjab_id', Crypt::decrypt($request->brandivjabkec));
        $kecs = $request->input('kecs');
        // dd($brandivjabkec);
        // dd($kecs);

        if ($kecs) {
            $brandivjabkec->delete();

            foreach ($kecs as $kec) {
                $prop = Kecamatan::find($kec);
                $brandivjabkec = Brandivjabkec::create([
                    'brandivjab_id' => $request->brandivjab_id,
                    'propinsi_id' => $prop->propinsi_id,
                    'kabupaten_id' => $prop->kabupaten_id,
                    'kecamatan_id' => $kec,
                    'keterangan' => $request->keterangan,
                    'isactive' => ($request->isactive == 'on' ? 1 : 0),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }

            if ($brandivjabkec) {
                return redirect()->route('brandivjabkec.edit', Crypt::encrypt($brandivjabkec->id))->with('success', __('messages.successupdated') . ' 👉 ' . $brandivjabkec->brandivjab->jabatan->nama . ' ' . $brandivjabkec->brandivjab->keterangan);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function delete(Request $request): View
    {
        $datas = Brandivjabkec::find(Crypt::decrypt($request->brandivjabkec));

        return view('brandivjabkec.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $brandivjabkec = Brandivjabkec::find(Crypt::decrypt($request->brandivjabkec));

        try {
            $brandivjabkec->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('brandivjabkec.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('brandivjabkec.index')->with('error', $e->getMessage());
        }

        return redirect()->route('brandivjabkec.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $brandivjabkec->nama);
    }
}
