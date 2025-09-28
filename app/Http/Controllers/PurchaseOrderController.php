<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\PurchaseOrderDetail;
use App\Models\Satuan;
use App\Http\Requests\PurchaseOrderRequest;
use App\Http\Requests\PurchaseOrderUpdateRequest;
use App\Models\Notif;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:po-list', only: ['index', 'fetch']),
            new Middleware('permission:po-create', only: ['create', 'store']),
            new Middleware('permission:po-edit', only: ['edit', 'update']),
            new Middleware('permission:po-show', only: ['show']),
            new Middleware('permission:po-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('purchase-order_pp')) {
            $request->session()->put('purchase-order_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('purchase-order_isactive')) {
            $request->session()->put('purchase-order_isactive', 'all');
        }
        if (!$request->session()->exists('purchase-order_tunai')) {
            $request->session()->put('purchase-order_tunai', 'all');
        }
        if (!$request->session()->exists('purchase-order_supplier_id')) {
            $request->session()->put('purchase-order_supplier_id', 'all');
        }
        if (!$request->session()->exists('purchase-order_tanggal')) {
            $request->session()->put('purchase-order_tanggal', '_');
        }
        if (!$request->session()->exists('purchase-order_no_order')) {
            $request->session()->put('purchase-order_no_order', '_');
        }

        $search_arr = ['purchase-order_isactive', 'purchase-order_tunai', 'purchase-order_supplier_id', 'purchase-order_no_order', 'purchase-order_tanggal'];

        // $datas = DB::table('purchase-orders');
        $branch_id = auth()->user()->profile->branch_id;
        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = PurchaseOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('purchase-order_'));

            if ($search_arr[$i] == 'purchase-order_isactive' || $search_arr[$i] == 'purchase-order_tunai' || $search_arr[$i] == 'purchase-order_supplier_id') {
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
        $datas = $datas->latest()->paginate(session('purchase-order_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('purchase-order.index', compact(['datas', 'suppliers']))->with('i', (request()->input('page', 1) - 1) * session('purchase-order_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('purchase-order_pp', $request->pp);
        $request->session()->put('purchase-order_isactive', $request->isactive);
        $request->session()->put('purchase-order_tunai', $request->tunai);
        $request->session()->put('purchase-order_supplier_id', $request->supplier);
        $request->session()->put('purchase-order_tanggal', $request->tanggal);
        $request->session()->put('purchase-order_no_order', $request->no_order);

        $search_arr = ['purchase-order_isactive', 'purchase-order_tunai', 'purchase-order_supplier_id', 'purchase-order_no_order', 'purchase-order_tanggal'];

        $branch_id = auth()->user()->profile->branch_id;
        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $datas = PurchaseOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('purchase-order_'));

            if ($search_arr[$i] == 'purchase-order_isactive' || $search_arr[$i] == 'purchase-order_tunai' || $search_arr[$i] == 'purchase-order_supplier_id') {
                if (session($search_arr[$i]) != 'all') {
                    $datas = $datas->where([$field => session($search_arr[$i])]);
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else if ($field == 'total_harga') {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->whereRaw("CONVERT(total_harga, CHAR) LIKE '" . $like . "'");
                } else if ($field == 'tanggal') {
                    $datas = $datas->where([$field => session($search_arr[$i])]);
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }

        $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        $datas = $datas->latest()->paginate(session('purchase-order_pp'));

        $datas->withPath('/purchase/order'); // pagination url to

        $view = view('purchase-order.partials.table', compact(['datas', 'suppliers']))->with('i', (request()->input('page', 1) - 1) * session('purchase-order_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(Request $request)
    {
        $branch_id = auth()->user()->profile->branch_id;
        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        // AUTO BUY BASE ON NOTIFICATION
        // if (count($request->all()) > 0) {
        //     if ($request->parm == 'notif_restock') {
        //         $notifs = Notif::where('isactive', 1)->where('route', 'purchase-order.create')->get(['route_parm']);

        //         $_po = PurchaseOrder::join('purchase_order_details', 'purchase_order_details.purchase_order_id', '=', 'purchase_orders.id')
        //             ->selectRaw('purchase_orders.supplier_id, COUNT(*) AS cnt')
        //             ->groupBy('purchase_orders.supplier_id')
        //             ->orderByRaw('cnt DESC')
        //             ->first();

        //         $po = PurchaseOrder::create([
        //             'branch_id' => auth()->user()->profile->branch_id,
        //             'supplier_id' => $_po->supplier_id,
        //             'tanggal' => date("Y-m-d"),
        //             'tunai' => 1,
        //             'isactive' => 1,
        //             'isaccepted' => 0,
        //             'created_by' => auth()->user()->email,
        //             'updated_by' => auth()->user()->email,
        //             'approved' => (config('custom.purchase_approval') == false) ? 1 : 0,
        //             'approved_by' => (config('custom.purchase_approval') == false) ? 'system' : NULL,
        //         ]);

        //         foreach ($notifs as $notif) {
        //             $barang = Barang::find($notif->route_parm);
        //             $satuan_id = $barang->satuan_beli_id;
        //             $harga_satuan = $barang->harga_satuan;
        //             $kuantiti = $barang->minstock * (1 + 0.10);
        //             // dd($barang);

        //             $detail = PurchaseOrderDetail::create([
        //                 'purchase_order_id' => $po->id,
        //                 'branch_id' => auth()->user()->profile->branch_id,
        //                 'barang_id' => $notif->route_parm,
        //                 'satuan_id' => $satuan_id,
        //                 'harga_satuan' => $harga_satuan,
        //                 'kuantiti' => $kuantiti,
        //                 'pajak' => 0,
        //                 'discount' => 0,
        //                 'isaccepted' => 0,
        //                 'satuan_terima_id' => $satuan_id,
        //                 'kuantiti_terima' => NULL,
        //                 'created_by' => auth()->user()->email,
        //                 'updated_by' => auth()->user()->email,
        //                 'approved' => (config('custom.purchase_approval') == false) ? 1 : 0,
        //                 'approved_by' => (config('custom.purchase_approval') == false) ? 'system' : NULL,
        //             ]);
        //         }

        //         return redirect()->route('purchase-order.edit', Crypt::encrypt($po->id));
        //     }
        // }

        return view('purchase-order.create', compact(['suppliers', 'branch_id']));
    }

    public function store(PurchaseOrderRequest $request): RedirectResponse
    {
        $biaya_angkutan = 0;
        $total_harga = 0;
        $tunai = 1;

        if ($request->validated()) {

            if ($request->biaya_angkutan) $biaya_angkutan = $request->biaya_angkutan;
            if ($request->total_harga) $total_harga = $request->total_harga;
            if ($request->tunai) $tunai = ($request->tunai == '2' ? 2 : 1);

            $po = PurchaseOrder::create([
                'branch_id' => $request->branch_id,
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'biaya_angkutan' => str_replace('.', '', str_replace('Rp. ', '', $biaya_angkutan)),
                'total_harga' => $total_harga,
                'tunai' => $tunai,
                'jatuhtempo' => $tunai == 2 ? $request->jatuhtempo : NULL,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'isaccepted' => 0,
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
                'approved' => (config('custom.purchase_approval') == false) ? 1 : 0,
                'approved_by' => (config('custom.purchase_approval') == false) ? 'system' : NULL,
            ]);

            return redirect()->route('purchase-order.edit', Crypt::encrypt($po->id));
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
        }
    }

    public function show(Request $request): View
    {
        $datas = PurchaseOrder::find(Crypt::decrypt($request->order));
        $details = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->order))->get();

        $total_price = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $datas->total_harga,
        ];

        return view('purchase-order.show', compact(['datas', 'details', 'totals']));
    }

    public function edit(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = PurchaseOrder::find(Crypt::decrypt($request->order));
        $details = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->order))->get();

        $total_price = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100) - (discount/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $datas->total_harga,
        ];

        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $barangs = Barang::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->orderBy('singkatan')->pluck('singkatan', 'id');

        return view('purchase-order.edit', compact(['datas', 'details', 'totals', 'suppliers', 'barangs', 'satuans', 'branch_id']));
    }

    public function update(PurchaseOrderUpdateRequest $request): RedirectResponse
    {
        $order = PurchaseOrder::find(Crypt::decrypt($request->order));

        $biaya_angkutan = 0;
        $tunai = 1;

        if ($request->validated()) {

            if ($request->biaya_angkutan) $biaya_angkutan = $request->biaya_angkutan;
            if ($request->tunai) $tunai = ($request->tunai == '2' ? 2 : 1);

            $order->update([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'biaya_angkutan' => str_replace('.', '', str_replace('Rp. ', '', $biaya_angkutan)),
                'no_order' => $request->no_order,
                'tunai' => $tunai,
                'jatuhtempo' => $tunai == 2 ? $request->jatuhtempo : NULL,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->no_order);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $datas = PurchaseOrder::find(Crypt::decrypt($request->order));

        $details = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->order))->get();

        $total_price = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $datas->total_harga,
        ];

        return view('purchase-order.delete', compact(['datas', 'details', 'totals']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $order = PurchaseOrder::find(Crypt::decrypt($request->order));

        try {
            $order->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('purchase-order.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('purchase-order.index')->with('error', $e->getMessage());
        }

        return redirect()->route('purchase-order.index')->with('success', __('messages.successdeleted') . ' 👉 ' . $order->no_order);
    }

    public function storeDetail(Request $request): JsonResponse
    {
        $order_id = $request->detail;
        $pajak = $request->pajak ? $request->pajak : 0;
        $discount = $request->discount ? $request->discount : 0;

        $detail = PurchaseOrderDetail::create([
            'purchase_order_id' => $order_id,
            'branch_id' => $request->branch_id,
            'barang_id' => $request->barang_id,
            'satuan_id' => $request->satuan_id,
            'kuantiti' => $request->kuantiti,
            'pajak' => $pajak,
            'discount' => $discount,
            'harga_satuan' => $request->harga_satuan,
            'keterangan' => $request->keterangan,
            'isaccepted' => 0,
            'satuan_terima_id' => $request->satuan_id,
            'kuantiti_terima' => NULL,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
            'approved' => (config('custom.purchase_approval') == false) ? 1 : 0,
            'approved_by' => (config('custom.purchase_approval') == false) ? 'system' : NULL,
        ]);

        $selaluUpdateHargaBeli = config('custom.selaluUpdateHargaBeli');

        if ($selaluUpdateHargaBeli) {
            $barang = Barang::find($request->barang_id);

            if ($barang) {
                $barang->update([
                    'satuan_beli_id' => $request->satuan_id,
                    'harga_satuan' => $request->harga_satuan,
                    'updated_by' => auth()->user()->email,
                ]);
            }
        }

        $po = PurchaseOrder::find($order_id);
        $total_price = PurchaseOrderDetail::where('purchase_order_id', $order_id)->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100) - (discount/100))) * kuantiti) as total_price'))->value('total_price');

        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $po->total_harga,
        ];

        // $po->update([
        //     'total_harga' => $totals['total_price'],
        // ]);

        $details = PurchaseOrderDetail::where('purchase_order_id', $order_id)->get();
        $viewMode = false;

        $view = view('purchase-order.partials.details', compact(['details', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
            'total_harga_master' => $totals['total_price'],
            'total_harga_detail' => $totals['sub_price'],
        ], 200);
    }

    public function deleteDetail(Request $request): JsonResponse
    {
        $detail = PurchaseOrderDetail::find($request->detail);
        $order = PurchaseOrder::where('id', $detail->purchase_order_id)->get();

        $order_id = $detail->purchase_order_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['QueryException' => $e->getMessage()], 500);
        }

        $po = PurchaseOrder::find($order_id);
        $total_price = PurchaseOrderDetail::where('purchase_order_id', $order_id)->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100) - (discount/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $po->total_harga,
        ];

        // $po->update([
        //     'total_harga' => $totals['total_price'],
        // ]);

        $details = PurchaseOrderDetail::where('purchase_order_id', $order_id)->get();
        $viewMode = false;

        if ($details->count() > 0) {
            $view = view('purchase-order.partials.details', compact(['details', 'viewMode']))->render();
        }

        if ($view) {
            return response()->json([
                'view' => $view,
                'total_harga_master' => $totals['total_price'],
                'total_harga_detail' => $totals['sub_price'],
            ], 200);
        } else {
            return response()->json([
                'status' => 'Not Found',
                'total_harga_master' => $totals['total_price'],
                'total_harga_detail' => $totals['sub_price'],
            ], 200);
        }
    }
}
