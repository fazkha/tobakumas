<?php

namespace App\Http\Controllers;

use App\Models\ProdOrder;
use App\Http\Requests\ProdOrderRequest;
use App\Models\Barang;
use App\Models\Pegawai;
use App\Models\ProdOrderDetail;
use App\Models\SaleOrder;
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
            $request->session()->put('production-order_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('production-order_selesai')) {
            $request->session()->put('production-order_selesai', 'all');
        }
        if (!$request->session()->exists('production-order_tanggal')) {
            $request->session()->put('production-order_tanggal', '_');
        }
        if (!$request->session()->exists('production-order_nomor')) {
            $request->session()->put('production-order_nomor', '_');
        }

        $search_arr = ['production-order_selesai', 'production-order_tanggal', 'production-order_nomor'];

        $datas = ProdOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('production-order_'));

            if ($search_arr[$i] == 'production-order_nomor') {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->whereRelation('order', 'no_order', 'LIKE', $like);
                }
            } else if ($search_arr[$i] == 'production-order_selesai') {
                if (session($search_arr[$i]) !== 'all') {
                    $datas = $datas->whereRelation('order', 'isready', session($search_arr[$i]));
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
        $request->session()->put('production-order_selesai', $request->pr);
        $request->session()->put('production-order_tanggal', $request->tanggal);
        $request->session()->put('production-order_nomor', $request->nomor);

        $search_arr = ['production-order_selesai', 'production-order_tanggal', 'production-order_nomor'];

        $datas = ProdOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('production-order_'));

            if ($search_arr[$i] == 'production-order_nomor') {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->whereRelation('order', 'no_order', 'LIKE', $like);
                }
            } else if ($search_arr[$i] == 'production-order_selesai') {
                if (session($search_arr[$i]) !== 'all') {
                    $datas = $datas->whereRelation('order', 'isready', session($search_arr[$i]));
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

    public function create()
    {
        //
    }

    public function store(ProdOrderRequest $request)
    {
        //
    }

    public function show(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = ProdOrder::find(Crypt::decrypt($request->order));
        $details = ProdOrderDetail::where('prod_order_id', Crypt::decrypt($request->order))->get();

        if ($datas->order->isready == 1) {
            $sales = SaleOrder::where('branch_id', $branch_id)->where('isactive', 1)->where('id', '<>', $datas->sale_order_id)
                ->where(function ($query) use ($request) {
                    $query->where('isready', 1)
                        ->whereIn('id', function ($query) use ($request) {
                            $query->select('sale_order_id')->from('prod_order_joins')->where('prod_order_id', Crypt::decrypt($request->order));
                        });
                })
                ->orderBy('no_order')->get();
        } else {
            $sales = SaleOrder::where('branch_id', $branch_id)->where('isactive', 1)->where('id', '<>', $datas->sale_order_id)->where('isready', 0)->orderBy('no_order')->get();
        }

        $syntax = 'CALL sp_hitung_bahanbaku_produksi(' . Crypt::decrypt($request->order) . ')';
        $bahans = DB::select($syntax);

        $syntax = 'CALL sp_target_produksi(' . Crypt::decrypt($request->order) . ')';
        $targets = DB::select($syntax);

        return view('production-order.show', compact(['datas', 'details', 'sales', 'bahans', 'targets']));
    }

    public function edit(Request $request)
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = ProdOrder::find(Crypt::decrypt($request->order));
        $details = ProdOrderDetail::where('prod_order_id', Crypt::decrypt($request->order))->get();

        $barangs = Barang::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        // islevel = 7 = staff; islevel = 3 = kepala divisi
        $petugas = ViewPegawaiJabatan::where('islevel', 7)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');
        $petugas2 = ViewPegawaiJabatan::where('islevel', 3)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');
        $satuans = Satuan::where('isactive', 1)->orderBy('singkatan')->pluck('singkatan', 'id');

        if ($datas->order->isready == 1) {
            $sales = SaleOrder::where('branch_id', $branch_id)->where('isactive', 1)->where('id', '<>', $datas->sale_order_id)
                ->where(function ($query) use ($request) {
                    $query->where('isready', 1)
                        ->whereIn('id', function ($query) use ($request) {
                            $query->select('sale_order_id')->from('prod_order_joins')->where('prod_order_id', Crypt::decrypt($request->order));
                        });
                })
                ->orderBy('no_order')->get();
        } else {
            $sales = SaleOrder::where('branch_id', $branch_id)->where('isactive', 1)->where('id', '<>', $datas->sale_order_id)->where('isready', 0)->orderBy('no_order')->get();
        }

        $syntax = 'CALL sp_hitung_bahanbaku_produksi(' . Crypt::decrypt($request->order) . ')';
        $bahans = DB::select($syntax);

        $syntax = 'CALL sp_target_produksi(' . Crypt::decrypt($request->order) . ')';
        $targets = DB::select($syntax);

        return view('production-order.edit', compact(['datas', 'details', 'barangs', 'satuans', 'petugas', 'petugas2', 'branch_id', 'sales', 'bahans', 'targets']));
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

    public function delete(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        //
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

        $syntax = 'CALL sp_target_produksi(' . $request->order . ')';
        $targets = DB::select($syntax);
        $view4 = view('production-order.partials.targets', compact(['targets']))->render();

        return response()->json([
            'view' => $view,
            'view2' => $view2,
            'view3' => $view3,
            'view4' => $view4,
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

    public function finishOrder(Request $request): JsonResponse
    {
        $syntax = 'CALL sp_finish_produksi(' . $request->order . ',\'' . auth()->user()->email . '\',' . auth()->user()->profile->branch_id . ')';

        $finish = DB::select($syntax);

        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
