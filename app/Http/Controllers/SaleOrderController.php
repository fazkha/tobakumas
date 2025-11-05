<?php

namespace App\Http\Controllers;

use App\Models\SaleOrder;
use App\Models\Customer;
use App\Models\Barang;
use App\Models\SaleOrderDetail;
use App\Models\Satuan;
use App\Models\SaleOrderMitra;
use Illuminate\Http\Request;
use App\Http\Requests\SaleOrderRequest;
use App\Http\Requests\SaleOrderUpdateRequest;
use App\Models\Brandivjab;
use App\Models\Brandivjabpeg;
use App\Models\Pegawai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SaleOrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:so-list', only: ['index', 'fetch']),
            new Middleware('permission:so-create', only: ['create', 'store']),
            new Middleware('permission:so-edit', only: ['edit', 'update']),
            new Middleware('permission:so-show', only: ['show']),
            new Middleware('permission:so-delete', only: ['delete', 'destroy']),
            new Middleware('permission:so-approval', only: ['approval', 'updateApproval']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('sale-order_pp')) {
            $request->session()->put('sale-order_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('sale-order_isactive')) {
            $request->session()->put('sale-order_isactive', 'all');
        }
        if (!$request->session()->exists('sale-order_tunai')) {
            $request->session()->put('sale-order_tunai', 'all');
        }
        if (!$request->session()->exists('sale-order_customer_id')) {
            $request->session()->put('sale-order_customer_id', 'all');
        }
        if (!$request->session()->exists('sale-order_tanggal')) {
            $request->session()->put('sale-order_tanggal', '_');
        }
        if (!$request->session()->exists('sale-order_no_order')) {
            $request->session()->put('sale-order_no_order', '_');
        }

        $search_arr = ['sale-order_isactive', 'sale-order_tunai', 'sale-order_customer_id', 'sale-order_no_order', 'sale-order_tanggal'];

        // $datas = DB::table('sale-orders');
        $branch_id = auth()->user()->profile->branch_id;
        $customers = Customer::where('branch_id', $branch_id)->where('isactive', 1)->pluck('nama', 'id');
        $datas = SaleOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('sale-order_'));

            if ($search_arr[$i] == 'sale-order_isactive' || $search_arr[$i] == 'sale-order_tunai' || $search_arr[$i] == 'sale-order_customer_id') {
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
        $datas = $datas->latest()->paginate(session('sale-order_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('sale-order.index', compact(['datas', 'customers']))->with('i', (request()->input('page', 1) - 1) * session('sale-order_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('sale-order_pp', $request->pp);
        $request->session()->put('sale-order_isactive', $request->isactive);
        $request->session()->put('sale-order_tunai', $request->tunai);
        $request->session()->put('sale-order_customer_id', $request->customer);
        $request->session()->put('sale-order_tanggal', $request->tanggal);
        $request->session()->put('sale-order_no_order', $request->no_order);

        $search_arr = ['sale-order_isactive', 'sale-order_tunai', 'sale-order_customer_id', 'sale-order_no_order', 'sale-order_tanggal'];

        $branch_id = auth()->user()->profile->branch_id;
        $customers = Customer::where('branch_id', $branch_id)->where('isactive', 1)->pluck('nama', 'id');
        $datas = SaleOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('sale-order_'));

            if ($search_arr[$i] == 'sale-order_isactive' || $search_arr[$i] == 'sale-order_tunai' || $search_arr[$i] == 'sale-order_customer_id') {
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
        $datas = $datas->latest()->paginate(session('sale-order_pp'));

        $datas->withPath('/sale/order'); // pagination url to

        $view = view('sale-order.partials.table', compact(['datas', 'customers']))->with('i', (request()->input('page', 1) - 1) * session('sale-order_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $customers = Customer::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        return view('sale-order.create', compact(['customers', 'branch_id']));
    }

    public function store(SaleOrderRequest $request)
    {
        $biaya_angkutan = 0;
        $total_harga = 0;
        $tunai = 1;
        $pajak = 0;

        if ($request->validated()) {

            if ($request->biaya_angkutan) $biaya_angkutan = $request->biaya_angkutan;
            if ($request->total_harga) $total_harga = $request->total_harga;
            if ($request->tunai) $tunai = ($request->tunai == '2' ? 2 : 1);
            if ($request->pajak) $pajak = $request->pajak;

            $so = SaleOrder::create([
                'branch_id' => $request->branch_id,
                'customer_id' => $request->customer_id,
                'hke' => $request->hke,
                'tanggal' => $request->tanggal,
                'biaya_angkutan' => str_replace('.', '', str_replace('Rp. ', '', $biaya_angkutan)),
                'total_harga' => $total_harga,
                'tunai' => $tunai,
                'jatuhtempo' => $tunai == 2 ? $request->jatuhtempo : NULL,
                'pajak' => $pajak,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
                'approved' => (config('custom.sale_approval') == false) ? 1 : 0,
                'approved_by' => (config('custom.sale_approval') == false) ? 'system' : NULL,
                'approved_at' => (config('custom.sale_approval') == false) ? date('Y-m-d H:i:s') : NULL,
            ]);

            return redirect()->route('sale-order.edit', Crypt::encrypt($so->id));
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
        }
    }

    public function show(Request $request): View
    {
        $datas = SaleOrder::find(Crypt::decrypt($request->order));
        $details = SaleOrderDetail::where('sale_order_id', Crypt::decrypt($request->order))->get();
        $adonans = SaleOrderMitra::where('sale_order_id', Crypt::decrypt($request->order))->get();

        $total_price = SaleOrderDetail::where('sale_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $total_price_adonan = SaleOrderMitra::where('sale_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'sub_price_adonan' => $total_price_adonan * 1,
            'total_price' => $datas->total_harga,
        ];

        // return view('sale-order.show', compact(['datas', 'details', 'totals', 'customers', 'barangs', 'satuans']));
        return view('sale-order.show', compact(['datas', 'details', 'adonans', 'totals']));
    }

    public function edit(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = SaleOrder::find(Crypt::decrypt($request->order));
        $details = SaleOrderDetail::where('sale_order_id', Crypt::decrypt($request->order))->get();
        $adonans = SaleOrderMitra::where('sale_order_id', Crypt::decrypt($request->order))->get();

        $total_price = SaleOrderDetail::where('sale_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $total_price_adonan = SaleOrderMitra::where('sale_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'sub_price_adonan' => $total_price_adonan * 1,
            'total_price' => $datas->total_harga,
        ];

        $customers = Customer::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        // jenis_barang_id = "Gula Pasir", 2, 7, 8, 9, 10 = barang-dagangan
        $barangs = Barang::where('branch_id', $branch_id)->where('isactive', 1)->where(function ($q) {
            $q->whereIn('jenis_barang_id', [2, 7, 8, 9, 10])
                ->orWhere('nama', 'like', '%gula pasir%');
        })->orderBy('nama')->pluck('nama', 'id');
        // jenis_barang_id = 4 = adonan
        $barang2s = Barang::where('branch_id', $branch_id)->where('isactive', 1)->where('jenis_barang_id', 4)->orderBy('nama')->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->orderBy('singkatan')->pluck('singkatan', 'id');
        // jabatan = mitra
        $syntax = 'CALL sp_mitra_order(' . '\'Mitra\'' . ',' . Crypt::decrypt($request->order) . ')';
        $pegawais = DB::select($syntax);

        return view('sale-order.edit', compact(['datas', 'details', 'totals', 'adonans', 'customers', 'barangs', 'barang2s', 'satuans', 'pegawais', 'branch_id']));
    }

    public function update(SaleOrderUpdateRequest $request): RedirectResponse
    {
        $order = SaleOrder::find(Crypt::decrypt($request->order));

        $pajak = 0;
        $biaya_angkutan = 0;
        $tunai = 1;

        if ($request->validated()) {

            if ($request->pajak) $pajak = $request->pajak;
            if ($request->biaya_angkutan) $biaya_angkutan = $request->biaya_angkutan;
            if ($request->tunai) $tunai = ($request->tunai == '2' ? 2 : 1);

            $order->update([
                'customer_id' => $request->customer_id,
                'hke' => $request->hke,
                'tanggal' => $request->tanggal,
                'biaya_angkutan' => str_replace('.', '', str_replace('Rp. ', '', $biaya_angkutan)),
                'no_order' => $request->no_order,
                'pajak' => str_replace(',', '.', str_replace('% ', '', $pajak)),
                'tunai' => $tunai,
                'jatuhtempo' => $tunai == 2 ? $request->jatuhtempo : NULL,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            $total_price = SaleOrderDetail::where('sale_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
            $total_price_adonan = SaleOrderMitra::where('sale_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
            $order = SaleOrder::find(Crypt::decrypt($request->order));
            $totals = [
                'sub_price' => $total_price * 1,
                'sub_price_adonan' => $total_price_adonan * 1,
                'total_price' => $order->total_harga,
            ];

            return redirect()->back()->with('totals', $totals)->with('success', __('messages.successupdated') . ' 👉 ' . $request->no_order);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $datas = SaleOrder::find(Crypt::decrypt($request->order));

        $details = SaleOrderDetail::where('sale_order_id', Crypt::decrypt($request->order))->get();
        $adonans = SaleOrderMitra::where('sale_order_id', Crypt::decrypt($request->order))->get();

        $total_price = SaleOrderDetail::where('sale_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $total_price_adonan = SaleOrderMitra::where('sale_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'sub_price_adonan' => $total_price_adonan * 1,
            'total_price' => $datas->total_harga,
        ];

        return view('sale-order.delete', compact(['datas', 'details', 'adonans', 'totals']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $order = SaleOrder::find(Crypt::decrypt($request->order));

        try {
            $order->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('sale-order.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('sale-order.index')->with('error', $e->getMessage());
        }

        return redirect()->route('sale-order.index')->with('success', __('messages.successdeleted') . ' 👉 ' . $order->no_order);
    }

    public function storeAdonan(Request $request)
    {
        $order_id = $request->detail;
        $pajak = $request->pajak_adonan ? $request->pajak_adonan : 0;
        // dd($order_id);

        $detail = SaleOrderMitra::create([
            'sale_order_id' => $order_id,
            'branch_id' => $request->branch_id,
            'pegawai_id' => $request->pegawai_id,
            'barang_id' => $request->barang_id_adonan,
            'satuan_id' => $request->satuan_id_adonan,
            'kuantiti' => $request->kuantiti_adonan,
            'nama_mitra' => $request->nama_mitra,
            'pajak' => $pajak,
            'harga_satuan' => $request->harga_satuan_adonan,
            'keterangan' => $request->keterangan_adonan,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
            'approved' => (config('custom.sale_approval') == false) ? 1 : 0,
            'approved_by' => (config('custom.sale_approval') == false) ? 'system' : NULL,
            'approved_at' => (config('custom.sale_approval') == false) ? date('Y-m-d H:i:s') : NULL,
        ]);

        $sale = SaleOrder::find($order_id);
        $customer = Customer::find($sale->customer_id);

        if ($customer->branch_link_id) {
            // jabatan_id = 3 = Mitra
            $brandivjab = Brandivjab::where('isactive', 1)
                ->where('jabatan_id', 3)
                ->where('branch_id', $customer->branch_link_id)
                ->first();

            if (!$brandivjab) {
                $brandivjab = Brandivjab::create([
                    'branch_id' => $customer->branch_link_id,
                    'jabatan_id' => 3,
                    'isactive' => 1,
                    'created_by' => auth()->user()->email,
                ]);
            }

            if ($brandivjab) {
                $pegawai = Pegawai::create([
                    'nama_lengkap' => $request->nama_mitra,
                    'nama_panggilan' => $request->nama_mitra,
                    'alamat_tinggal' => '-',
                    'telpon' => '-',
                    'kelamin' => 'L',
                    'isactive' => 1,
                    'created_by' => 'PenjualanMitra',
                ]);

                Brandivjabpeg::create([
                    'brandivjab_id' => $brandivjab->id,
                    'pegawai_id' => $pegawai->id,
                    'isactive' => 1,
                    'tanggal_mulai' => date('Y-m-d'),
                    'created_by' => auth()->user()->email,
                ]);

                $detail->update([
                    'pegawai_id' => $pegawai->id,
                ]);
            }
        }

        $selaluUpdateHargaJual = config('custom.selaluUpdateHargaJual');

        if ($selaluUpdateHargaJual) {
            $barang = Barang::find($request->barang_id);

            if ($barang) {
                $barang->update([
                    'satuan_jual_id' => $request->satuan_id_adonan,
                    'harga_satuan_jual' => $request->harga_satuan_adonan,
                    'updated_by' => auth()->user()->email,
                ]);
            }
        }

        $po = SaleOrder::find($order_id);
        $total_price = SaleOrderMitra::where('sale_order_id', $order_id)->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $po->total_harga,
        ];

        $adonans = SaleOrderMitra::where('sale_order_id', $order_id)->get();
        $viewMode = false;

        $view = view('sale-order.partials.details-adonan', compact(['adonans', 'viewMode']))->render();

        $syntax = 'CALL sp_mitra_order(' . '\'Mitra\'' . ',' . $order_id . ')';
        $pegawais = DB::select($syntax);

        return response()->json([
            'view' => $view,
            'total_harga_master' => $totals['total_price'],
            'total_harga_adonan' => $totals['sub_price'],
            'pegawais' => $pegawais,
        ], 200);
    }

    public function deleteAdonan(Request $request): JsonResponse
    {
        $detail = SaleOrderMitra::find($request->detail);
        $order = SaleOrder::where('id', $detail->sale_order_id)->get();

        $order_id = $detail->sale_order_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['QueryException' => $e->getMessage()], 500);
        }

        $po = SaleOrder::find($order_id);
        $total_price = SaleOrderMitra::where('sale_order_id', $order_id)->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $po->total_harga,
        ];

        $adonans = SaleOrderMitra::where('sale_order_id', $order_id)->get();
        $viewMode = false;

        if ($adonans->count() > 0) {
            $view = view('sale-order.partials.details-adonan', compact(['adonans', 'viewMode']))->render();
        }

        if ($view) {
            return response()->json([
                'view' => $view,
                'total_harga_master' => $totals['total_price'],
                'total_harga_adonan' => $totals['sub_price'],
            ], 200);
        } else {
            return response()->json([
                'status' => 'Not Found',
                'total_harga_master' => $totals['total_price'],
                'total_harga_adonan' => $totals['sub_price'],
            ], 200);
        }
    }

    public function storeDetail(Request $request)
    {
        $order_id = $request->detail;
        $pajak = $request->pajak ? $request->pajak : 0;
        // dd($order_id);

        $detail = SaleOrderDetail::create([
            'sale_order_id' => $order_id,
            'branch_id' => $request->branch_id,
            'barang_id' => $request->barang_id,
            'satuan_id' => $request->satuan_id,
            'kuantiti' => $request->kuantiti,
            'stock' => $request->stock,
            'pajak' => $pajak,
            'harga_satuan' => $request->harga_satuan,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
            'approved' => (config('custom.sale_approval') == false) ? 1 : 0,
            'approved_by' => (config('custom.sale_approval') == false) ? 'system' : NULL,
            'approved_at' => (config('custom.sale_approval') == false) ? date('Y-m-d H:i:s') : NULL,
        ]);

        $selaluUpdateHargaJual = config('custom.selaluUpdateHargaJual');

        if ($selaluUpdateHargaJual) {
            $barang = Barang::find($request->barang_id);

            if ($barang) {
                $barang->update([
                    'satuan_jual_id' => $request->satuan_id,
                    'harga_satuan_jual' => $request->harga_satuan,
                    'updated_by' => auth()->user()->email,
                ]);
            }
        }

        $po = SaleOrder::find($order_id);
        $total_price = SaleOrderDetail::where('sale_order_id', $order_id)->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $po->total_harga,
        ];

        // $po->update([
        //     'total_harga' => $totals['total_price'],
        // ]);

        $details = SaleOrderDetail::where('sale_order_id', $order_id)->get();
        $viewMode = false;

        $view = view('sale-order.partials.details', compact(['details', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
            'total_harga_master' => $totals['total_price'],
            'total_harga_detail' => $totals['sub_price'],
        ], 200);
    }

    public function deleteDetail(Request $request): JsonResponse
    {
        $detail = SaleOrderDetail::find($request->detail);
        $order = SaleOrder::where('id', $detail->sale_order_id)->get();

        $order_id = $detail->sale_order_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['QueryException' => $e->getMessage()], 500);
        }

        $po = SaleOrder::find($order_id);
        $total_price = SaleOrderDetail::where('sale_order_id', $order_id)->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'total_price' => $po->total_harga,
        ];

        // $po->update([
        //     'total_harga' => $totals['total_price'],
        // ]);

        $details = SaleOrderDetail::where('sale_order_id', $order_id)->get();
        $viewMode = false;

        if ($details->count() > 0) {
            $view = view('sale-order.partials.details', compact(['details', 'viewMode']))->render();
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

    public function approval(Request $request)
    {
        if (config('custom.sale_approval') == true) {
            $datas = SaleOrder::find(Crypt::decrypt($request->order));
            $details = SaleOrderDetail::where('sale_order_id', Crypt::decrypt($request->order))->get();

            $total_price = SaleOrderDetail::where('sale_order_id', Crypt::decrypt($request->order))->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
            $totals = [
                'sub_price' => $total_price * 1,
                'total_price' => $datas->total_harga,
            ];

            return view('sale-order.approval', compact(['datas', 'details', 'totals']));
        }
    }

    public function updateApproval(Request $request)
    {
        if (config('custom.sale_approval') == true) {
            $detail_id = $request->detail;
            $detail = SaleOrderDetail::find($detail_id);
            $order_id = $detail->sale_order_id;

            if ($detail) {
                $detail->update([
                    'approved' => $request->status,
                    'approved_by' => auth()->user()->email,
                ]);
            }

            $po = SaleOrder::find($order_id);
            $total_price = SaleOrderDetail::where('sale_order_id', $order_id)->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
            $totals = [
                'sub_price' => $total_price * 1,
                'total_price' => $po->total_harga,
            ];

            $details = SaleOrderDetail::where('sale_order_id', $order_id)->get();
            $viewMode = false;

            $view = view('sale-order.partials.details-approval', compact(['details', 'viewMode']))->render();

            return response()->json([
                'view' => $view,
                'total_harga_master' => $totals['total_price'],
                'total_harga_detail' => $totals['sub_price'],
            ], 200);
        }
    }
}
