<?php

namespace App\Http\Controllers;

// TIDAK TERPAKAI !!!

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderDetail;
use App\Models\DeliveryOrderMitra;
use App\Models\Paket;
use App\Models\Pegawai;
use App\Models\Barang;
use App\Models\JenisBarang;
use App\Http\Requests\DeliveryOrderRequest;
use App\Http\Requests\DeliveryOrderUpdateRequest;
use App\Models\DeliveryPackage;
use App\Models\Satuan;
use App\Models\ViewPegawaiJabatan;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller implements HasMiddleware
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
        if (!$request->session()->exists('delivery-order_pp')) {
            $request->session()->put('delivery-order_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('delivery-order_isactive')) {
            $request->session()->put('delivery-order_isactive', 'all');
        }
        if (!$request->session()->exists('delivery-order_kode')) {
            $request->session()->put('delivery-order_kode', '_');
        }
        if (!$request->session()->exists('delivery-order_nama')) {
            $request->session()->put('delivery-order_nama', '_');
        }
        if (!$request->session()->exists('delivery-order_alamat')) {
            $request->session()->put('delivery-order_alamat', '_');
        }

        $search_arr = ['delivery-order_isactive', 'delivery-order_kode', 'delivery-order_nama', 'delivery-order_alamat'];

        $datas = DeliveryOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('delivery-order_'));

            if ($search_arr[$i] == 'delivery-order_isactive') {
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
        $datas = $datas->latest()->paginate(session('delivery-order_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('delivery-order.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('delivery-order_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('delivery-order_pp', $request->pp);
        $request->session()->put('delivery-order_isactive', $request->isactive);
        $request->session()->put('delivery-order_kode', $request->kode);
        $request->session()->put('delivery-order_nama', $request->nama);
        $request->session()->put('delivery-order_alamat', $request->alamat);

        $search_arr = ['delivery-order_isactive', 'delivery-order_kode', 'delivery-order_nama', 'delivery-order_alamat'];

        $datas = DeliveryOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('delivery-order_'));

            if ($search_arr[$i] == 'delivery-order_isactive') {
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
        $datas = $datas->latest()->paginate(session('delivery-order_pp'));

        $datas->withPath('/delivery/order'); // pagination url to

        $view = view('delivery-order.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('delivery-order_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create()
    {
        //
    }

    public function store(DeliveryOrderRequest $request)
    {
        //
    }

    public function show(Request $request): View
    {
        $datas = DeliveryOrder::find(Crypt::decrypt($request->order));
        $details = DeliveryOrderDetail::where('delivery_order_id', Crypt::decrypt($request->order))->get();
        $mitras = DeliveryOrderMitra::where('delivery_order_id', Crypt::decrypt($request->order))->get();
        $pakets = Paket::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        $syntax = 'CALL sp_hitung_kemasan(' . Crypt::decrypt($request->order) . ',' . auth()->user()->profile->branch_id . ')';
        $kemasans = DB::select($syntax);

        return view('delivery-order.show', compact(['datas', 'pakets', 'details', 'mitras', 'kemasans']));
    }

    public function edit(Request $request): View
    {
        $datas = DeliveryOrder::find(Crypt::decrypt($request->order));
        $details = DeliveryOrderDetail::where('delivery_order_id', Crypt::decrypt($request->order))->get();
        $mitras = DeliveryOrderMitra::where('delivery_order_id', Crypt::decrypt($request->order))->get();
        // islevel = 7 = staff; islevel = 3 = kepala divisi
        $petugas = ViewPegawaiJabatan::where('islevel', 7)->where('kode_branch', main_office_code())->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');
        $petugas2 = ViewPegawaiJabatan::where('islevel', 3)->where('kode_branch', main_office_code())->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');
        $jenis = JenisBarang::where('nama', 'Packaging')->first();
        $satuanJenis = Barang::where('isactive', 1)->where('jenis_barang_id', $jenis->id)->first('satuan_stock_id');
        $barangs = Barang::where('isactive', 1)->where('jenis_barang_id', $jenis->id)->orderBy('nama')->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->where('id', $satuanJenis->satuan_stock_id)->pluck('singkatan', 'id');
        $packages = DeliveryPackage::where('delivery_order_id', Crypt::decrypt($request->order))->get();

        $total_price = DeliveryPackage::where('delivery_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM(harga_satuan * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => 0,
        ];

        return view('delivery-order.edit', compact(['datas', 'petugas', 'petugas2', 'barangs', 'satuans', 'details', 'mitras', 'packages', 'totals']));
    }

    public function update(DeliveryOrderUpdateRequest $request): RedirectResponse
    {
        $delivery = DeliveryOrder::find(Crypt::decrypt($request->order));

        if ($request->validated()) {

            $delivery->update([
                'petugas_1_id' => $request->petugas_1_id,
                'petugas_2_id' => $request->petugas_2_id,
                'pengirim_id' => $request->pengirim_id,
                'tanggungjawab_id' => $request->tanggungjawab_id,
                'tanggal' => $request->tanggal,
                'alamat' => $request->alamat,
                'keterangan' => $request->keterangan,
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->alamat);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function storePackage(Request $request)
    {
        // dd($request->all());
        $dp = DeliveryPackage::create([
            'branch_id' => auth()->user()->profile->branch_id,
            'delivery_order_id' => $request->delivery_order_id,
            'sale_order_id' => $request->sale_order_id,
            'barang_id' => $request->barang_id,
            'satuan_id' => $request->satuan_id,
            'harga_satuan' => $request->harga_satuan,
            'kuantiti' => $request->kuantiti,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
        ]);

        $total_price = DeliveryPackage::where('delivery_order_id', $request->delivery_order_id)->select(DB::raw('SUM(harga_satuan * kuantiti) as total_price'))->value('total_price');

        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => 0,
        ];

        $packages = DeliveryPackage::where('delivery_order_id', $request->delivery_order_id)->get();
        $viewMode = false;

        $view = view('delivery-order.partials.details', compact(['packages', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
            'total_harga_detail' => $totals['sub_price'],
        ], 200);
    }

    public function deletePackage(Request $request): JsonResponse
    {
        $detail = DeliveryPackage::find($request->package);
        $order = DeliveryOrder::where('id', $detail->delivery_order_id)->get();

        $order_id = $detail->delivery_order_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['QueryException' => $e->getMessage()], 500);
        }

        $total_price = DeliveryPackage::where('delivery_order_id', $order_id)->select(DB::raw('SUM((harga_satuan * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => 0,
        ];

        // $po->update([
        //     'total_harga' => $totals['total_price'],
        // ]);

        $packages = DeliveryPackage::where('delivery_order_id', $order_id)->get();
        $viewMode = false;

        if ($packages->count() > 0) {
            $view = view('delivery-order.partials.details', compact(['packages', 'viewMode']))->render();
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

    public function delete(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        //
    }

    public function finishOrder(Request $request): JsonResponse
    {
        $syntax = 'CALL sp_finish_pengemasan(' . $request->order . ',\'' . auth()->user()->email . '\',' . auth()->user()->profile->branch_id . ')';

        $finish = DB::select($syntax);

        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
