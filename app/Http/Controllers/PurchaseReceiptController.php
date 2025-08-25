<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\PurchaseOrderDetail;
use App\Models\Satuan;
use App\Http\Requests\PurchaseReceiptRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PurchaseReceiptController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:purchasereceipt-list', only: ['index', 'fetch']),
            new Middleware('permission:purchasereceipt-create', only: ['create', 'store']),
            new Middleware('permission:purchasereceipt-edit', only: ['edit', 'update']),
            new Middleware('permission:purchasereceipt-show', only: ['show']),
            new Middleware('permission:purchasereceipt-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('purchase-receipt_pp')) {
            $request->session()->put('purchase-receipt_pp', 12);
        }
        if (!$request->session()->exists('purchase-receipt_isactive')) {
            $request->session()->put('purchase-receipt_isactive', 'all');
        }
        if (!$request->session()->exists('purchase-receipt_tunai')) {
            $request->session()->put('purchase-receipt_tunai', 'all');
        }
        if (!$request->session()->exists('purchase-receipt_supplier_id')) {
            $request->session()->put('purchase-receipt_supplier_id', 'all');
        }
        if (!$request->session()->exists('purchase-receipt_tanggal')) {
            $request->session()->put('purchase-receipt_tanggal', '_');
        }
        if (!$request->session()->exists('purchase-receipt_no_order')) {
            $request->session()->put('purchase-receipt_no_order', '_');
        }

        $search_arr = ['purchase-receipt_isactive', 'purchase-receipt_tunai', 'purchase-receipt_supplier_id', 'purchase-receipt_no_order', 'purchase-receipt_tanggal'];

        // $datas = DB::table('purchase-receipts');
        $branch_id = auth()->user()->profile->branch_id;
        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->pluck('nama', 'id');
        $datas = PurchaseOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('purchase-receipt_'));

            if ($search_arr[$i] == 'purchase-receipt_isactive' || $search_arr[$i] == 'purchase-receipt_tunai' || $search_arr[$i] == 'purchase-receipt_supplier_id') {
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
        $datas = $datas->latest()->paginate(session('purchase-receipt_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('purchase-receipt.index', compact(['datas', 'suppliers']))->with('i', (request()->input('page', 1) - 1) * session('purchase-receipt_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('purchase-receipt_pp', $request->pp);
        $request->session()->put('purchase-receipt_isactive', $request->isactive);
        $request->session()->put('purchase-receipt_tunai', $request->tunai);
        $request->session()->put('purchase-receipt_supplier_id', $request->supplier);
        $request->session()->put('purchase-receipt_tanggal', $request->tanggal);
        $request->session()->put('purchase-receipt_no_order', $request->no_order);

        $search_arr = ['purchase-receipt_isactive', 'purchase-receipt_tunai', 'purchase-receipt_supplier_id', 'purchase-receipt_no_order', 'purchase-receipt_tanggal'];

        $branch_id = auth()->user()->profile->branch_id;
        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->pluck('nama', 'id');
        $datas = PurchaseOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('purchase-receipt_'));

            if ($search_arr[$i] == 'purchase-receipt_isactive' || $search_arr[$i] == 'purchase-receipt_tunai' || $search_arr[$i] == 'purchase-receipt_supplier_id') {
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
        $datas = $datas->latest()->paginate(session('purchase-receipt_pp'));

        $datas->withPath('/warehouse/purchase-receipt'); // pagination url to

        $view = view('purchase-receipt.partials.table', compact(['datas', 'suppliers']))->with('i', (request()->input('page', 1) - 1) * session('purchase-receipt_pp'))->render();

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

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request): View
    {
        $datas = PurchaseOrder::find(Crypt::decrypt($request->purchase_receipt));
        $details = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->purchase_receipt))->get();

        $total_price = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->purchase_receipt))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $datas->total_harga,
        ];

        return view('purchase-receipt.show', compact(['datas', 'details', 'totals']));
    }

    public function edit(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = PurchaseOrder::find(Crypt::decrypt($request->purchase_receipt));
        $details = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->purchase_receipt))->get();

        $total_price = PurchaseOrderDetail::where('purchase_order_id', Crypt::decrypt($request->purchase_receipt))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100) - (discount/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $datas->total_harga,
        ];

        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $barangs = Barang::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->orderBy('singkatan')->pluck('singkatan', 'id');

        return view('purchase-receipt.edit', compact(['datas', 'details', 'totals', 'suppliers', 'barangs', 'satuans', 'branch_id']));
    }

    public function update(PurchaseReceiptRequest $request): RedirectResponse
    {
        $order = PurchaseOrder::find(Crypt::decrypt($request->purchase_receipt));

        if ($request->validated()) {
            $order->update([
                'tanggal_terima' => $request->tanggal_terima,
                'isaccepted' => ($request->isaccepted == 'on' ? 1 : 0),
                'keterangan_terima' => $request->keterangan_terima,
                'petugas_terima_id' => $request->petugas_terima_id,
                'tanggungjawab_terima_id' => $request->tanggungjawab_terima_id,
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->no_order);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
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

    public function updateDetail(Request $request): JsonResponse
    {
        $master_id = $request->detail;
        $items = $request->input('items');

        foreach ($items as $item) {
            if (array_key_exists('isaccepted', $item)) {
                $isaccepted = $item['isaccepted'] == 'on' ? 1 : 0;
            } else {
                $isaccepted = 0;
            }

            $detail = PurchaseOrderDetail::where('purchase_order_id', $master_id)->where('id', $item['id']);

            $detail->update([
                'isaccepted' => $isaccepted,
                'keterangan_terima' => $item['keterangan_terima'],
                'satuan_terima_id' => $item['satuan_terima_id'],
                'kuantiti_terima' => $item['kuantiti_terima'],
                'updated_by' => auth()->user()->email,
            ]);
        }

        $details = PurchaseOrderDetail::where('purchase_order_id', $master_id)->get();
        $satuans = Satuan::where('isactive', 1)->orderBy('singkatan')->pluck('singkatan', 'id');
        $viewMode = false;

        $view = view('purchase-receipt.partials.details', compact(['details', 'satuans', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
        ], 200);
    }
}
