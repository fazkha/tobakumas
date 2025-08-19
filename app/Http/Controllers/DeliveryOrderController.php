<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderDetail;
use App\Models\DeliveryOrderMitra;
use App\Models\Paket;
use App\Models\Pegawai;
use App\Models\Barang;
use App\Models\JenisBarang;
use App\Http\Requests\DeliveryOrderRequest;
use App\Http\Requests\DeliveryOrderUpdateRequest;
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
            $request->session()->put('delivery-order_pp', 5);
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
        // $petugas = Pegawai::where('isactive', 1)->orderBy('nama_lengkap')->pluck('nama_lengkap', 'id');
        $petugas = ViewPegawaiJabatan::pluck('nama_plus', 'pegawai_id');
        $pakets = Paket::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $jenis = JenisBarang::where('nama', 'Packaging')->first();
        $satuanJenis = Barang::where('isactive', 1)->where('jenis_barang_id', $jenis->id)->first('satuan_stock_id');
        $barangs = Barang::where('isactive', 1)->where('jenis_barang_id', $jenis->id)->orderBy('nama')->pluck('nama', 'id');
        $satuans = Satuan::where('isactive', 1)->where('id', $satuanJenis->satuan_stock_id)->pluck('singkatan', 'id');

        $syntax = 'CALL sp_hitung_kemasan(' . Crypt::decrypt($request->order) . ',' . auth()->user()->profile->branch_id . ')';
        $kemasans = DB::select($syntax);

        return view('delivery-order.edit', compact(['datas', 'petugas', 'pakets', 'barangs', 'satuans', 'details', 'mitras', 'kemasans']));
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
