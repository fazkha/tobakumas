<?php

namespace App\Http\Controllers;

use App\Models\ProdOrder;
use App\Http\Requests\ProdOrderRequest;
use App\Models\Barang;
use App\Models\Pegawai;
use App\Models\ProdOrderDetail;
use App\Models\SaleOrder;
use App\Models\Satuan;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ProdOrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:prodo-list', only: ['index', 'fetch']),
            new Middleware('permission:prodo-create', only: ['create', 'store']),
            new Middleware('permission:prodo-edit', only: ['edit', 'update']),
            new Middleware('permission:prodo-show', only: ['show']),
            new Middleware('permission:prodo-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('production-order_pp')) {
            $request->session()->put('production-order_pp', 5);
        }
        if (!$request->session()->exists('production-order_tanggal')) {
            $request->session()->put('production-order_tanggal', '_');
        }

        $search_arr = ['production-order_tanggal'];

        $datas = ProdOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('production-order_'));

            if ($search_arr[$i] == 'production-order_branch_id') {
                if (session($search_arr[$i]) !== 'all') {
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

        $datas = $datas->where('isactive', 1)->where('branch_id', auth()->user()->profile->branch_id);
        $datas = $datas->latest()->paginate(session('production-order_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('production-order.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('production-order_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('production-order_pp', $request->pp);
        $request->session()->put('production-order_tanggal', $request->tanggal);

        $search_arr = ['production-order_tanggal'];

        $datas = ProdOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('production-order_'));

            if ($search_arr[$i] == 'production-order_branch_id') {
                if (session($search_arr[$i]) !== 'all') {
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

        $datas = $datas->where('isactive', 1)->where('branch_id', auth()->user()->profile->branch_id);
        $datas = $datas->latest()->paginate(session('production-order_pp'));

        $datas->withPath('/production/production-order'); // pagination url to

        $view = view('production-order.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('production-order_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $petugas = Pegawai::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');

        return view('production-order.create', compact(['petugas', 'branch_id']));
    }

    public function store(ProdOrderRequest $request)
    {
        if ($request->validated()) {
            $order = ProdOrder::create([
                'branch_id' => $request->branch_id,
                'tanggal' => $request->tanggal,
                'petugas_1_id' => $request->petugas_1_id,
                'petugas_2_id' => $request->petugas_2_id,
                'tanggungjawab_id' => $request->tanggungjawab_id,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->route('production-order.edit', Crypt::encrypt($order->id));
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
        }
    }

    public function show(Request $request): View
    {
        $datas = ProdOrder::find(Crypt::decrypt($request->order));
        $details = ProdOrderDetail::where('prod_order_id', Crypt::decrypt($request->order))->get();

        return view('production-order.show', compact(['datas', 'details']));
    }

    public function edit(Request $request)
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = ProdOrder::find(Crypt::decrypt($request->order));
        $details = ProdOrderDetail::where('prod_order_id', Crypt::decrypt($request->order))->get();

        $barangs = Barang::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $petugas = Pegawai::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');
        $satuans = Satuan::where('isactive', 1)->orderBy('singkatan')->pluck('singkatan', 'id');
        $sales = SaleOrder::where('branch_id', $branch_id)->where('isactive', 1)->where('isready', 0)->where('id', '<>', $datas->sale_order_id)->orderBy('no_order')->get();

        $syntax = 'CALL sp_hitung_bahanbaku_produksi(' . Crypt::decrypt($request->order) . ')';
        $bahans = DB::select($syntax);

        return view('production-order.edit', compact(['datas', 'details', 'barangs', 'satuans', 'petugas', 'branch_id', 'sales', 'bahans']));
    }

    public function update(ProdOrderRequest $request): RedirectResponse
    {
        $order = ProdOrder::find(Crypt::decrypt($request->order));

        if ($request->validated()) {
            $order->update([
                'tanggal' => $request->tanggal,
                'petugas_1_id' => $request->petugas_1_id,
                'petugas_2_id' => $request->petugas_2_id,
                'tanggungjawab_id' => $request->tanggungjawab_id,
                'keterangan' => $request->keterangan,
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->tanggal);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $datas = ProdOrder::find(Crypt::decrypt($request->order));
        $details = ProdOrderDetail::where('prod_order_id', Crypt::decrypt($request->order))->get();

        return view('production-order.delete', compact(['datas', 'details']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $order = ProdOrder::find(Crypt::decrypt($request->order));

        try {
            $order->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('production-order.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('production-order.index')->with('error', $e->getMessage());
        }

        return redirect()->route('production-order.index')->with('success', __('messages.successdeleted') . ' 👉 ' . $order->tanggal);
    }

    public function combineJoin(Request $request): JsonResponse
    {
        $syntax = 'CALL sp_prod_combine(' . $request->order . ',' . $request->join . ')';
        $results = DB::select($syntax);

        $master = ProdOrder::find($request->order);
        $details = ProdOrderDetail::where('prod_order_id', $request->order)->get();
        $sales = SaleOrder::where('branch_id', auth()->user()->profile->branch_id)->where('isactive', 1)->where('isready', 0)->where('id', '<>', $master->sale_order_id)->orderBy('no_order')->get();
        $viewMode = true;

        $view = view('production-order.partials.details', compact(['details', 'viewMode']))->render();
        $view2 = view('production-order.partials.combines', compact(['sales']))->render();

        $syntax = 'CALL sp_hitung_bahanbaku_produksi(' . $request->order . ')';
        $bahans = DB::select($syntax);
        $view3 = view('production-order.partials.bahanbakuproduksi', compact(['bahans']))->render();

        return response()->json([
            'view' => $view,
            'view2' => $view2,
            'view3' => $view3,
        ], 200);
    }

    public function hitungBahanbakuProduksi(Request $request): JsonResponse
    {
        $syntax = 'CALL sp_hitung_bahanbaku_produksi(' . $request->order . ')';

        $bahans = DB::select($syntax);

        $view = view('production-order.partials.bahanbakuproduksi', compact(['bahans']))->render();

        return response()->json([
            'view' => $view,
        ], 200);
    }
}
