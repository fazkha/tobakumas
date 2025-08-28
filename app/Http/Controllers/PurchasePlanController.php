<?php

namespace App\Http\Controllers;

use App\Models\PurchasePlan;
use App\Models\PurchasePlanDetail;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\Satuan;
use App\Http\Requests\PurchasePlanRequest;
use App\Http\Requests\PurchasePlanUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Arr;

class PurchasePlanController extends Controller implements HasMiddleware
{
    protected $array_bulan;

    public function __construct()
    {
        $this->array_bulan = [
            ['bulan' => ['id' => 1, 'name' => __('messages.january')]],
            ['bulan' => ['id' => 2, 'name' => __('messages.february')]],
            ['bulan' => ['id' => 3, 'name' => __('messages.march')]],
            ['bulan' => ['id' => 4, 'name' => __('messages.apryl')]],
            ['bulan' => ['id' => 5, 'name' => __('messages.may')]],
            ['bulan' => ['id' => 6, 'name' => __('messages.june')]],
            ['bulan' => ['id' => 7, 'name' => __('messages.july')]],
            ['bulan' => ['id' => 8, 'name' => __('messages.august')]],
            ['bulan' => ['id' => 9, 'name' => __('messages.september')]],
            ['bulan' => ['id' => 10, 'name' => __('messages.october')]],
            ['bulan' => ['id' => 11, 'name' => __('messages.november')]],
            ['bulan' => ['id' => 12, 'name' => __('messages.december')]],
        ];
    }

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
        if (!$request->session()->exists('purchase-plan_pp')) {
            $request->session()->put('purchase-plan_pp', 12);
        }
        if (!$request->session()->exists('purchase-plan_isactive')) {
            $request->session()->put('purchase-plan_isactive', 'all');
        }
        if (!$request->session()->exists('purchase-plan_supplier_id')) {
            $request->session()->put('purchase-plan_supplier_id', 'all');
        }
        if (!$request->session()->exists('purchase-plan_periode_bulan')) {
            $request->session()->put('purchase-plan_periode_bulan', 'all');
        }
        if (!$request->session()->exists('purchase-plan_periode_tahun')) {
            $request->session()->put('purchase-plan_periode_tahun', '_');
        }

        $search_arr = ['purchase-plan_isactive', 'purchase-plan_supplier_id', 'purchase-plan_periode_tahun', 'purchase-plan_periode_bulan'];

        $branch_id = auth()->user()->profile->branch_id;
        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $bulans = Arr::pluck($this->array_bulan, 'bulan.name', 'bulan.id');
        $datas = PurchasePlan::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('purchase-plan_'));

            if ($search_arr[$i] == 'purchase-plan_isactive' || $search_arr[$i] == 'purchase-plan_periode_bulan' || $search_arr[$i] == 'purchase-plan_supplier_id') {
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
        $datas = $datas->orderBy('periode_tahun', 'desc')->orderBy('periode_bulan', 'desc')->paginate(session('purchase-plan_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('purchase-plan.index', compact(['datas', 'suppliers', 'bulans']))->with('i', (request()->input('page', 1) - 1) * session('purchase-plan_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('purchase-plan_pp', $request->pp);
        $request->session()->put('purchase-plan_isactive', $request->isactive);
        $request->session()->put('purchase-plan_supplier_id', $request->supplier);
        $request->session()->put('purchase-plan_periode_tahun', $request->tahun);
        $request->session()->put('purchase-plan_periode_bulan', $request->bulan);

        $search_arr = ['purchase-plan_isactive', 'purchase-plan_supplier_id', 'purchase-plan_periode_tahun', 'purchase-plan_periode_bulan'];

        $branch_id = auth()->user()->profile->branch_id;
        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $bulans = Arr::pluck($this->array_bulan, 'bulan.name', 'bulan.id');
        $datas = PurchasePlan::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('purchase-plan_'));

            if ($search_arr[$i] == 'purchase-plan_isactive' || $search_arr[$i] == 'purchase-plan_periode_bulan' || $search_arr[$i] == 'purchase-plan_supplier_id') {
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
        $datas = $datas->orderBy('periode_tahun', 'desc')->orderBy('periode_bulan', 'desc')->paginate(session('purchase-plan_pp'));

        $datas->withPath('/purchase/plan'); // pagination url to

        $view = view('purchase-plan.partials.table', compact(['datas', 'suppliers', 'bulans']))->with('i', (request()->input('page', 1) - 1) * session('purchase-plan_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $bulans = Arr::pluck($this->array_bulan, 'bulan.name', 'bulan.id');

        return view('purchase-plan.create', compact(['suppliers', 'branch_id', 'bulans']));
    }

    public function store(PurchasePlanRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $po = PurchasePlan::create([
                'branch_id' => $request->branch_id,
                'supplier_id' => $request->supplier_id,
                'periode_bulan' => $request->periode_bulan,
                'periode_tahun' => $request->periode_tahun,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->route('purchase-plan.edit', Crypt::encrypt($po->id));
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
        }
    }

    public function show(Request $request): View
    {
        $datas = PurchasePlan::find(Crypt::decrypt($request->plan));
        $details = PurchasePlanDetail::where('purchase_plan_id', Crypt::decrypt($request->plan))->get();
        $bulans = Arr::pluck($this->array_bulan, 'bulan.name', 'bulan.id');

        return view('purchase-plan.show', compact(['datas', 'details', 'bulans']));
    }

    public function edit(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = PurchasePlan::find(Crypt::decrypt($request->plan));
        $details = PurchasePlanDetail::where('purchase_plan_id', Crypt::decrypt($request->plan))->get();

        $suppliers = Supplier::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $barangs = Barang::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->orderBy('singkatan')->pluck('singkatan', 'id');
        $bulans = Arr::pluck($this->array_bulan, 'bulan.name', 'bulan.id');

        return view('purchase-plan.edit', compact(['datas', 'details', 'suppliers', 'barangs', 'satuans', 'branch_id', 'bulans']));
    }

    public function update(PurchasePlanUpdateRequest $request): RedirectResponse
    {
        $order = PurchasePlan::find(Crypt::decrypt($request->plan));

        if ($request->validated()) {
            $order->update([
                'supplier_id' => $request->supplier_id,
                'periode_bulan' => $request->periode_bulan,
                'periode_tahun' => $request->periode_tahun,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $order->supplier->nama);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $datas = PurchasePlan::find(Crypt::decrypt($request->plan));
        $details = PurchasePlanDetail::where('purchase_plan_id', Crypt::decrypt($request->plan))->get();
        $bulans = Arr::pluck($this->array_bulan, 'bulan.name', 'bulan.id');

        return view('purchase-plan.delete', compact(['datas', 'details', 'bulans']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $order = PurchasePlan::find(Crypt::decrypt($request->plan));

        try {
            $order->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('purchase-plan.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('purchase-plan.index')->with('error', $e->getMessage());
        }

        return redirect()->route('purchase-plan.index')->with('success', __('messages.successdeleted') . ' 👉 ' . $order->supplier->nama);
    }

    public function storeDetail(Request $request): JsonResponse
    {
        $order_id = $request->detail;

        $detail = PurchasePlanDetail::create([
            'purchase_plan_id' => $order_id,
            'branch_id' => $request->branch_id,
            'barang_id' => $request->barang_id,
            'satuan_id' => $request->satuan_id,
            'kuantiti' => $request->kuantiti,
            'sisa_kuota' => $request->kuantiti,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
        ]);

        $details = PurchasePlanDetail::where('purchase_plan_id', $order_id)->get();
        $viewMode = false;

        $view = view('purchase-plan.partials.details', compact(['details', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
        ], 200);
    }

    public function deleteDetail(Request $request): JsonResponse
    {
        $detail = PurchasePlanDetail::find($request->detail);
        $order = PurchasePlan::where('id', $detail->purchase_plan_id)->get();

        $order_id = $detail->purchase_plan_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'Not Found'], 404);
        }

        $details = PurchasePlanDetail::where('purchase_plan_id', $order_id)->get();
        $viewMode = false;

        if ($details->count() > 0) {
            $view = view('purchase-plan.partials.details', compact(['details', 'viewMode']))->render();
        }

        if ($view) {
            return response()->json([
                'view' => $view,
            ], 200);
        } else {
            return response()->json([
                'status' => 'Not Found',
            ], 200);
        }
    }
}
