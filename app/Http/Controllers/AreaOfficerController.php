<?php

namespace App\Http\Controllers;

use App\Models\AreaOfficer;
use App\Models\Customer;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Propinsi;
use App\Models\ViewPegawaiJabatan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class AreaOfficerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:delivery-list', only: ['index', 'fetch']),
            new Middleware('permission:delivery-create', only: ['create', 'store']),
            new Middleware('permission:delivery-edit', only: ['edit', 'update']),
            new Middleware('permission:delivery-show', only: ['show']),
            new Middleware('permission:delivery-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('area-officer_pp')) {
            $request->session()->put('area-officer_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('area-officer_isactive')) {
            $request->session()->put('area-officer_isactive', 'all');
        }
        if (!$request->session()->exists('area-officer_propinsi_id')) {
            $request->session()->put('area-officer_propinsi_id', 'all');
        }
        if (!$request->session()->exists('area-officer_kabupaten_id')) {
            $request->session()->put('area-officer_kabupaten_id', 'all');
        }

        $search_arr = ['area-officer_isactive', 'area-officer_propinsi_id', 'area-officer_kabupaten_id'];

        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = AreaOfficer::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('area-officer_'));

            if ($search_arr[$i] == 'area-officer_isactive' || $search_arr[$i] == 'area-officer_propinsi_id' || $search_arr[$i] == 'area-officer_kabupaten_id') {
                if (session($search_arr[$i]) != 'all') {
                    if ($search_arr[$i] == 'area-officer_propinsi_id') {
                        $datas = $datas->whereRelation('customer', 'propinsi_id', session($search_arr[$i]));
                    } elseif ($search_arr[$i] == 'area-officer_kabupaten_id') {
                        // dd(session($search_arr[$i]));
                        $datas = $datas->whereRelation('customer', 'kabupaten_id', session($search_arr[$i]));
                    } else {
                        $datas = $datas->where([$field => session($search_arr[$i])]);
                    }
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }

        $datas = $datas->selectRaw('area_officers.pegawai_id, area_officers.keterangan, area_officers.isactive, pegawais.nama_lengkap')
            ->join('pegawais', 'pegawais.id', '=', 'area_officers.pegawai_id')
            ->orderBy('pegawais.nama_lengkap')
            ->distinct();
        $datas = $datas->paginate(session('area-officer_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('area-officer.index', compact(['datas', 'propinsis', 'kabupatens']))->with('i', (request()->input('page', 1) - 1) * session('area-officer_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('area-officer_pp', $request->pp);
        $request->session()->put('area-officer_isactive', $request->isactive);
        $request->session()->put('area-officer_propinsi_id', $request->propinsi);
        $request->session()->put('area-officer_kabupaten_id', $request->kabupaten);

        $search_arr = ['area-officer_isactive', 'area-officer_propinsi_id', 'area-officer_kabupaten_id'];

        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = AreaOfficer::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('area-officer_'));

            if ($search_arr[$i] == 'area-officer_isactive' || $search_arr[$i] == 'area-officer_propinsi_id' || $search_arr[$i] == 'area-officer_kabupaten_id') {
                if (session($search_arr[$i]) != 'all') {
                    if ($search_arr[$i] == 'area-officer_propinsi_id') {
                        $datas = $datas->whereRelation('customer', 'propinsi_id', session($search_arr[$i]));
                    } elseif ($search_arr[$i] == 'area-officer_kabupaten_id') {
                        $datas = $datas->whereRelation('customer', 'kabupaten_id', session($search_arr[$i]));
                    } else {
                        $datas = $datas->where([$field => session($search_arr[$i])]);
                    }
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }

        $datas = $datas->selectRaw('area_officers.pegawai_id, area_officers.keterangan, area_officers.isactive, pegawais.nama_lengkap')
            ->join('pegawais', 'pegawais.id', '=', 'area_officers.pegawai_id')
            ->orderBy('pegawais.nama_lengkap')
            ->distinct();
        $datas = $datas->paginate(session('area-officer_pp'));

        $datas->withPath('/delivery/officer'); // pagination url to

        $view = view('area-officer.partials.table', compact(['datas', 'propinsis', 'kabupatens']))->with('i', (request()->input('page', 1) - 1) * session('area-officer_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $customers = Customer::join('kabupatens', 'kabupatens.id', 'customers.kabupaten_id')
            ->join('propinsis', 'propinsis.id', 'customers.propinsi_id')
            ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as namakabupaten, customers.nama as nama, customers.id as id')
            ->where('customers.isactive', 1)->where('kabupatens.isactive', 1)->where('propinsis.isactive', 1)
            ->orderBy('customers.propinsi_id')->orderBy('customers.kabupaten_id')->orderBy('customers.nama')->get();
        // level 7 = staf
        $petugas = ViewPegawaiJabatan::where('islevel', 7)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');

        return view('area-officer.create', compact(['customers', 'petugas']));
    }

    public function store(Request $request): RedirectResponse
    {
        $custs = $request->input('custs');

        if ($custs) {
            foreach ($custs as $cust) {
                $sel = Customer::find($cust);
                $area_officer = AreaOfficer::create([
                    'customer_id' => $cust,
                    'pegawai_id' => $request->pegawai_id,
                    'keterangan' => $request->keterangan,
                    'isactive' => ($request->isactive == 'on' ? 1 : 0),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }

            if ($area_officer) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $area_officer->pegawai->nama_lengkap);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request)
    {
        $data1 = AreaOfficer::where('pegawai_id', Crypt::decrypt($request->officer))->first();

        if ($data1) {
            $datas =  AreaOfficer::join('customers', 'customers.id', 'area_officers.customer_id')
                ->where('pegawai_id', $data1->pegawai_id)
                ->orderBy('customers.propinsi_id')
                ->orderBy('customers.kabupaten_id')
                ->orderBy('area_officers.customer_id')
                ->get();
            $customers = Customer::join('kabupatens', 'kabupatens.id', 'customers.kabupaten_id')
                ->join('propinsis', 'propinsis.id', 'customers.propinsi_id')
                ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as namakabupaten, customers.nama as nama, customers.id as id')
                ->where('customers.isactive', 1)->where('kabupatens.isactive', 1)->where('propinsis.isactive', 1)
                ->orderBy('customers.propinsi_id')->orderBy('customers.kabupaten_id')->orderBy('customers.nama')->get();
            // level 7 = staf
            $petugas = ViewPegawaiJabatan::where('islevel', 7)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');

            return view('area-officer.show', compact(['datas', 'customers', 'petugas']));
        }

        return redirect()->back();
    }

    public function edit(Request $request)
    {
        $data1 = AreaOfficer::where('pegawai_id', Crypt::decrypt($request->officer))->first();

        if ($data1) {
            $datas =  AreaOfficer::join('customers', 'customers.id', 'area_officers.customer_id')
                ->where('area_officers.pegawai_id', $data1->pegawai_id)
                ->orderBy('customers.propinsi_id')
                ->orderBy('customers.kabupaten_id')
                ->orderBy('area_officers.customer_id')
                ->selectRaw('area_officers.id AS id, area_officers.pegawai_id AS pegawai_id, area_officers.customer_id AS customer_id, area_officers.keterangan AS keterangan, area_officers.isactive AS isactive')
                ->get();
            $customers = Customer::join('kabupatens', 'kabupatens.id', 'customers.kabupaten_id')
                ->join('propinsis', 'propinsis.id', 'customers.propinsi_id')
                ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as namakabupaten, customers.nama as nama, customers.id as id')
                ->where('customers.isactive', 1)->where('kabupatens.isactive', 1)->where('propinsis.isactive', 1)
                ->orderBy('customers.propinsi_id')->orderBy('customers.kabupaten_id')->orderBy('customers.id')->get();
            // level 7 = staf
            $petugas = ViewPegawaiJabatan::where('islevel', 7)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');

            return view('area-officer.edit', compact(['datas', 'customers', 'petugas']));
        }

        return redirect()->back();
    }

    public function update(Request $request): RedirectResponse
    {
        $areaofficer = AreaOfficer::where('pegawai_id', Crypt::decrypt($request->officer));
        $custs = $request->input('custs');

        if ($custs) {
            $areaofficer->delete();

            foreach ($custs as $cust) {
                $areaofficer = AreaOfficer::create([
                    'customer_id' => $cust,
                    'pegawai_id' => $request->pegawai_id,
                    'keterangan' => $request->keterangan,
                    'isactive' => ($request->isactive == 'on' ? 1 : 0),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }

            if ($areaofficer) {
                return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $areaofficer->pegawai->nama_lengkap);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function updateDetail(Request $request): RedirectResponse
    {
        $areaofficer = AreaOfficer::where('pegawai_id', Crypt::decrypt($request->officer));
        $custs = $request->input('custs');

        if ($custs) {
            $areaofficer->delete();

            foreach ($custs as $cust) {
                $areaofficer = AreaOfficer::create([
                    'customer_id' => $cust,
                    'pegawai_id' => $request->pegawai_id,
                    'keterangan' => $request->keterangan,
                    'isactive' => ($request->isactive == 'on' ? 1 : 0),
                    'created_by' => auth()->user()->email,
                    'updated_by' => auth()->user()->email,
                ]);
            }

            if ($areaofficer) {
                return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $areaofficer->pegawai->nama_lengkap);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function delete(Request $request): RedirectResponse
    {
        return redirect()->back();
    }

    // public function destroy(Request $request): RedirectResponse
    // {
    //     $area - officer = Brandivjabkec::find(Crypt::decrypt($request->area - officer));

    //     try {
    //         $area - officer->delete();
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
    //             return redirect()->route('area-officer.index')->with('error', 'Integrity constraint violation');
    //         }
    //         return redirect()->route('area-officer.index')->with('error', $e->getMessage());
    //     }

    //     return redirect()->route('area-officer.index')
    //         ->with('success', __('messages.successdeleted') . ' 👉 ' . $area - officer->nama);
    // }
}
