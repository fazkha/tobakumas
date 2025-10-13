<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\DeliveryOfficer;
use App\Models\DeliveryPackage;
use App\Models\JenisBarang;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Propinsi;
use App\Models\Satuan;
use App\Models\ViewPegawaiJabatan;
use App\Http\Requests\DeliveryOfficerUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class DeliveryOfficerController extends Controller implements HasMiddleware
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
        if (!$request->session()->exists('delivery-officer_pp')) {
            $request->session()->put('delivery-officer_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('delivery-officer_isdone')) {
            $request->session()->put('delivery-officer_isdone', 'all');
        }
        if (!$request->session()->exists('delivery-officer_propinsi_id')) {
            $request->session()->put('delivery-officer_propinsi_id', 'all');
        }
        if (!$request->session()->exists('delivery-officer_kabupaten_id')) {
            $request->session()->put('delivery-officer_kabupaten_id', 'all');
        }

        $search_arr = ['delivery-officer_isdone', 'delivery-officer_propinsi_id', 'delivery-officer_kabupaten_id'];

        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = DeliveryOfficer::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('delivery-officer_'));

            if ($search_arr[$i] == 'delivery-officer_isdone' || $search_arr[$i] == 'delivery-officer_propinsi_id' || $search_arr[$i] == 'delivery-officer_kabupaten_id') {
                if (session($search_arr[$i]) != 'all') {
                    if ($search_arr[$i] == 'delivery-officer_propinsi_id') {
                        // $datas = $datas->whereRelation('customer', 'propinsi_id', session($search_arr[$i]));
                    } elseif ($search_arr[$i] == 'delivery-officer_kabupaten_id') {
                        // $datas = $datas->whereRelation('customer', 'kabupaten_id', session($search_arr[$i]));
                    } else {
                        $datas = $datas->where('area_officers.isdone', session($search_arr[$i]));
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

        $datas = $datas->paginate(session('delivery-officer_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('delivery-officer.index', compact(['datas', 'propinsis', 'kabupatens']))->with('i', (request()->input('page', 1) - 1) * session('delivery-officer_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('delivery-officer_pp', $request->pp);
        $request->session()->put('delivery-officer_isdone', $request->isactive);
        $request->session()->put('delivery-officer_propinsi_id', $request->propinsi);
        $request->session()->put('delivery-officer_kabupaten_id', $request->kabupaten);

        $search_arr = ['delivery-officer_isdone', 'delivery-officer_propinsi_id', 'delivery-officer_kabupaten_id'];

        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = DeliveryOfficer::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('delivery-officer_'));

            if ($search_arr[$i] == 'delivery-officer_isdone' || $search_arr[$i] == 'delivery-officer_propinsi_id' || $search_arr[$i] == 'delivery-officer_kabupaten_id') {
                if (session($search_arr[$i]) != 'all') {
                    if ($search_arr[$i] == 'delivery-officer_propinsi_id') {
                        // $datas = $datas->whereRelation('customer', 'propinsi_id', session($search_arr[$i]));
                    } elseif ($search_arr[$i] == 'delivery-officer_kabupaten_id') {
                        // $datas = $datas->whereRelation('customer', 'kabupaten_id', session($search_arr[$i]));
                    } else {
                        $datas = $datas->where('delivery_officers.isdone', session($search_arr[$i]));
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

        $datas = $datas->paginate(session('delivery-officer_pp'));

        $datas->withPath('/delivery/order'); // pagination url to

        $view = view('delivery-officer.partials.table', compact(['datas', 'propinsis', 'kabupatens']))->with('i', (request()->input('page', 1) - 1) * session('delivery-officer_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create()
    {
        return redirect()->back();
    }

    public function store(Request $request)
    {
        return redirect()->back();
    }

    public function show(Request $request)
    {
        $datas = DeliveryOfficer::find(Crypt::decrypt($request->order));
        $details = DeliveryPackage::where('delivery_officer_id', Crypt::decrypt($request->order))->get();

        $total_price = DeliveryPackage::where('delivery_officer_id', Crypt::decrypt($request->order))->select(DB::raw('SUM(harga_satuan * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => 0,
        ];

        $customers = Customer::join('kabupatens', 'kabupatens.id', 'customers.kabupaten_id')
            ->join('propinsis', 'propinsis.id', 'customers.propinsi_id')
            ->join('area_officers', function ($join) use ($datas) {
                $join->on('customers.id', '=', 'area_officers.customer_id')
                    ->where('area_officers.pegawai_id', '=', $datas->pegawai_id);
            })
            ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as namakabupaten, customers.nama as nama, customers.id as id')
            ->where('customers.isactive', 1)->where('kabupatens.isactive', 1)->where('propinsis.isactive', 1)
            ->orderBy('customers.propinsi_id')->orderBy('customers.kabupaten_id')->orderBy('customers.nama')->get();

        return view('delivery-officer.show', compact(['datas', 'details', 'totals', 'customers']));
    }

    public function edit(Request $request)
    {
        $datas = DeliveryOfficer::find(Crypt::decrypt($request->order));
        $details = DeliveryPackage::where('delivery_officer_id', Crypt::decrypt($request->order))->get();

        $jenis = JenisBarang::where('nama', 'Packaging')->first();
        $satuanJenis = Barang::where('isactive', 1)->where('jenis_barang_id', $jenis->id)->first('satuan_stock_id');
        $barangs = Barang::where('isactive', 1)->where('jenis_barang_id', $jenis->id)->orderBy('nama')->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->where('id', $satuanJenis->satuan_stock_id)->pluck('singkatan', 'id');

        $total_price = DeliveryPackage::where('delivery_officer_id', Crypt::decrypt($request->order))->select(DB::raw('SUM(harga_satuan * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => 0,
        ];

        $customers = Customer::join('kabupatens', 'kabupatens.id', 'customers.kabupaten_id')
            ->join('propinsis', 'propinsis.id', 'customers.propinsi_id')
            ->join('area_officers', function ($join) use ($datas) {
                $join->on('customers.id', '=', 'area_officers.customer_id')
                    ->where('area_officers.pegawai_id', '=', $datas->pegawai_id);
            })
            ->selectRaw('propinsis.nama as namapropinsi, kabupatens.nama as namakabupaten, customers.nama as nama, customers.id as id')
            ->where('customers.isactive', 1)->where('kabupatens.isactive', 1)->where('propinsis.isactive', 1)
            ->orderBy('customers.propinsi_id')->orderBy('customers.kabupaten_id')->orderBy('customers.nama')->get();

        return view('delivery-officer.edit', compact(['datas', 'details', 'barangs', 'satuans', 'totals', 'customers']));
    }

    public function update(DeliveryOfficerUpdateRequest $request): RedirectResponse
    {
        $delivery = DeliveryOfficer::find(Crypt::decrypt($request->order));

        if ($request->validated()) {

            $delivery->update([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $delivery->no_order);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function storePackage(Request $request)
    {
        $dp = DeliveryPackage::create([
            'delivery_officer_id' => $request->delivery_officer_id,
            'barang_id' => $request->barang_id,
            'satuan_id' => $request->satuan_id,
            'harga_satuan' => $request->harga_satuan,
            'kuantiti' => $request->kuantiti,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
        ]);

        $total_price = DeliveryPackage::where('delivery_officer_id', $request->delivery_officer_id)->select(DB::raw('SUM(harga_satuan * kuantiti) as total_price'))->value('total_price');

        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => 0,
        ];

        $details = DeliveryPackage::where('delivery_officer_id', $request->delivery_officer_id)->get();
        $viewMode = false;

        $view = view('delivery-officer.partials.details', compact(['details', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
            'total_harga_detail' => $totals['sub_price'],
        ], 200);
    }

    public function deletePackage(Request $request): JsonResponse
    {
        $detail = DeliveryPackage::find($request->package);
        $order = DeliveryOfficer::where('id', $detail->delivery_officer_id)->get();

        $order_id = $detail->delivery_officer_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['QueryException' => $e->getMessage()], 500);
        }

        $total_price = DeliveryPackage::where('delivery_officer_id', $order_id)->select(DB::raw('SUM(harga_satuan * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => 0,
        ];

        $details = DeliveryPackage::where('delivery_officer_id', $order_id)->get();
        $viewMode = false;

        if ($details->count() > 0) {
            $view = view('delivery-officer.partials.details', compact(['details', 'viewMode']))->render();
        }

        if ($view) {
            return response()->json([
                'view' => $view,
                'total_harga_detail' => $totals['sub_price'],
            ], 200);
        } else {
            return response()->json([
                'status' => 'Not Found',
                'total_harga_detail' => $totals['sub_price'],
            ], 200);
        }
    }

    public function delete(Request $request): RedirectResponse
    {
        return redirect()->back();
    }
}
