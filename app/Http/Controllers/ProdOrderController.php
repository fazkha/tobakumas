<?php

namespace App\Http\Controllers;

use App\Models\ProdOrder;
use App\Models\Barang;
use App\Models\Pegawai;
use App\Models\ProdOrderDetail;
use App\Models\SaleOrder;
use App\Models\Satuan;
use App\Models\ViewPegawaiJabatan;
use App\Models\ViewLaporanProduksi;
use App\Http\Requests\ProdOrderRequest;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdOrderController extends Controller implements HasMiddleware
{
    protected $array_hari, $array_bulan;

    public function __construct()
    {
        $this->array_hari = [
            ['hari' => ['id' => 0, 'name' => __('calendar.sunday')]],
            ['hari' => ['id' => 1, 'name' => __('calendar.monday')]],
            ['hari' => ['id' => 2, 'name' => __('calendar.tuesday')]],
            ['hari' => ['id' => 3, 'name' => __('calendar.wednesday')]],
            ['hari' => ['id' => 4, 'name' => __('calendar.thursday')]],
            ['hari' => ['id' => 5, 'name' => __('calendar.friday')]],
            ['hari' => ['id' => 6, 'name' => __('calendar.saturday')]],
        ];

        $this->array_bulan = [
            ['bulan' => ['id' => 1, 'name' => __('calendar.january')]],
            ['bulan' => ['id' => 2, 'name' => __('calendar.february')]],
            ['bulan' => ['id' => 3, 'name' => __('calendar.march')]],
            ['bulan' => ['id' => 4, 'name' => __('calendar.apryl')]],
            ['bulan' => ['id' => 5, 'name' => __('calendar.may')]],
            ['bulan' => ['id' => 6, 'name' => __('calendar.june')]],
            ['bulan' => ['id' => 7, 'name' => __('calendar.july')]],
            ['bulan' => ['id' => 8, 'name' => __('calendar.august')]],
            ['bulan' => ['id' => 9, 'name' => __('calendar.september')]],
            ['bulan' => ['id' => 10, 'name' => __('calendar.october')]],
            ['bulan' => ['id' => 11, 'name' => __('calendar.november')]],
            ['bulan' => ['id' => 12, 'name' => __('calendar.december')]],
        ];
    }

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
        if (!$request->session()->exists('production-order_periode_bulan')) {
            $request->session()->put('production-order_periode_bulan', 'all');
        }
        if (!$request->session()->exists('production-order_periode_tahun')) {
            $request->session()->put('production-order_periode_tahun', '_');
        }
        if (!$request->session()->exists('production-order_customer')) {
            $request->session()->put('production-order_customer', 'all');
        }

        $search_arr = ['production-order_selesai', 'production-order_tanggal', 'production-order_nomor', 'production-order_customer'];

        $bulans = Arr::pluck($this->array_bulan, 'bulan.name', 'bulan.id');
        $customers = Customer::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
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
            } else if ($search_arr[$i] == 'production-order_customer') {
                if (session($search_arr[$i]) !== 'all') {
                    $datas = $datas->whereRelation('order', 'customer_id', session($search_arr[$i]));
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }

        if (session('production-order_tanggal') == '_' or session('production-order_tanggal') == '') {
            if (session('production-order_periode_bulan') == 'all' and session('production-order_periode_tahun') != '_') {
                $datas = $datas->whereRaw('YEAR(tanggal) = ?', [session('production-order_periode_tahun')]);
            } elseif (session('production-order_periode_bulan') != 'all' and session('production-order_periode_tahun') != '_') {
                $datas = $datas->whereRaw('MONTH(tanggal) = ?', [session('production-order_periode_bulan')])
                    ->whereRaw('YEAR(tanggal) = ?', [session('production-order_periode_tahun')]);
            }
        }

        $datas = $datas->where('isactive', 1)->where('branch_id', auth()->user()->profile->branch_id);
        $datas = $datas->latest()->paginate(session('production-order_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('production-order.index', compact(['datas', 'bulans', 'customers']))->with('i', (request()->input('page', 1) - 1) * session('production-order_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('production-order_pp', $request->pp);
        $request->session()->put('production-order_selesai', $request->pr);
        $request->session()->put('production-order_tanggal', $request->tanggal);
        $request->session()->put('production-order_nomor', $request->nomor);
        $request->session()->put('production-order_periode_tahun', $request->tahun);
        $request->session()->put('production-order_periode_bulan', $request->bulan);
        $request->session()->put('production-order_customer', $request->customer);

        $search_arr = ['production-order_selesai', 'production-order_tanggal', 'production-order_nomor', 'production-order_customer'];

        $bulans = Arr::pluck($this->array_bulan, 'bulan.name', 'bulan.id');
        $customers = Customer::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
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
            } else if ($search_arr[$i] == 'production-order_customer') {
                if (session($search_arr[$i]) !== 'all') {
                    $datas = $datas->whereRelation('order', 'customer_id', session($search_arr[$i]));
                }
            } else {
                if (session($search_arr[$i]) == '_' or session($search_arr[$i]) == '') {
                } else {
                    $like = '%' . session($search_arr[$i]) . '%';
                    $datas = $datas->where($field, 'LIKE', $like);
                }
            }
        }

        if (session('production-order_tanggal') == '_' or session('production-order_tanggal') == '') {
            if (session('production-order_periode_bulan') == 'all' and session('production-order_periode_tahun') != '_') {
                $datas = $datas->whereRaw('YEAR(tanggal) = ?', [session('production-order_periode_tahun')]);
            } elseif (session('production-order_periode_bulan') != 'all' and session('production-order_periode_tahun') != '_') {
                $datas = $datas->whereRaw('MONTH(tanggal) = ?', [session('production-order_periode_bulan')])
                    ->whereRaw('YEAR(tanggal) = ?', [session('production-order_periode_tahun')]);
            }
        }

        $datas = $datas->where('isactive', 1)->where('branch_id', auth()->user()->profile->branch_id);

        // $sql = $datas->toSql();
        // $bindings = $datas->getBindings();
        // foreach ($bindings as $binding) {
        //     $sql = preg_replace('/\?/', "'" . addslashes($binding) . "'", $sql, 1);
        // }
        // dd($sql);

        $datas = $datas->latest()->paginate(session('production-order_pp'));

        $datas->withPath('/production/production-order'); // pagination url to

        $view = view('production-order.partials.table', compact(['datas', 'bulans', 'customers']))->with('i', (request()->input('page', 1) - 1) * session('production-order_pp'))->render();

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
        $satuanTarget = $targets[0]->satuan;

        return view('production-order.edit', compact(['datas', 'details', 'barangs', 'satuans', 'petugas', 'petugas2', 'branch_id', 'sales', 'bahans', 'targets', 'satuanTarget']));
    }

    public function update(ProdOrderRequest $request): RedirectResponse
    {
        $order = ProdOrder::find(Crypt::decrypt($request->order));

        if ($request->validated()) {
            $jumlah_rusak = $request->jumlah_rusak ? $request->jumlah_rusak : 0;
            $jumlah_sasaran = $request->jumlah_sasaran ? $request->jumlah_sasaran : 0;
            $sisa_persediaan = $request->sisa_persediaan ? $request->sisa_persediaan : 0;

            $order->update([
                'tanggal' => $request->tanggal,
                'jumlah_rusak' => $jumlah_rusak,
                'jumlah_sasaran' => $jumlah_sasaran,
                'petugas_1_id' => $request->petugas_1_id,
                'petugas_2_id' => $request->petugas_2_id,
                'tanggungjawab_id' => $request->tanggungjawab_id,
                'keterangan' => $request->keterangan,
                'updated_by' => auth()->user()->email,
            ]);
            // 'sisa_persediaan' => $sisa_persediaan,

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
        $order = ProdOrder::find($request->order);

        if ($order) {
            $jumlah_rusak = $request->jumlah_rusak ? $request->jumlah_rusak : 0;
            $jumlah_sasaran = $request->jumlah_sasaran ? $request->jumlah_sasaran : 0;

            $order->update([
                'tanggal' => $request->tanggal,
                'jumlah_rusak' => $jumlah_rusak,
                'jumlah_sasaran' => $jumlah_sasaran,
                'petugas_1_id' => $request->petugas_1_id,
                'petugas_2_id' => $request->petugas_2_id,
                'tanggungjawab_id' => $request->tanggungjawab_id,
                'keterangan' => $request->keterangan,
                'updated_by' => auth()->user()->email,
            ]);
        }

        $syntax = 'CALL sp_finish_produksi(' . $request->order . ',\'' . auth()->user()->email . '\',' . auth()->user()->profile->branch_id . ')';

        $finish = DB::select($syntax);

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function printRekap(Request $request)
    {
        $ntanggal = $request['search-tanggal'];
        $ntahun = $request['search-tahun']; // date('Y');
        $nbulan = $request['bulan-dropdown']; // date('n');
        $ncust = $request['customer-dropdown'];

        $nbulanini = date('n');
        $nhari = date('w');
        $hari = $this->array_hari[$nhari]['hari']['name'];
        if ($nbulan == 'all') {
            $bulan = 'Semua';
        } else {
            $bulan = $this->array_bulan[$nbulan - 1]['bulan']['name'];
        }
        $bulanini = $this->array_bulan[$nbulanini - 1]['bulan']['name'];

        if ($ntanggal) {
            $datas = ViewLaporanProduksi::where('c17', $ntanggal);
        } else {
            if ($ntahun == '') {
                $datas = ViewLaporanProduksi::get();
            } elseif ($nbulan == 'all') {
                $datas = ViewLaporanProduksi::where('c15', $ntahun);
            } else {
                $datas = ViewLaporanProduksi::where('c15', $ntahun)->where('c16', $nbulan);
            }
        }

        if ($ncust !== 'all') {
            $datas = $datas->where('c18', $ncust);
            $mcust = Customer::find($ncust);
            $cust = $mcust->nama;
        } else {
            $cust = __('messages.all');
        }

        $datas = $datas->get();

        $namafile = '_laporanproduksi_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        session()->put('documents', $namafile);

        if (count($datas) > 0) {
            $pdf = Pdf::loadView('production-order.pdf.lap-prod', ['datas' => $datas, 'bulan' => $bulan, 'bulanini' => $bulanini, 'cust' => $cust])
                ->setPaper('a4', 'landscape')
                ->setOptions(['enable_php' => true]);

            $output = $pdf->output();
            Storage::disk('pdfs')->put($namafile, $output);

            return response()->json([
                'namafile' => url('documents/' . $namafile . '?v=' . time()),
            ], 200);
        }

        return response()->json([
            'status' => 'Not Found',
        ], 200);
    }

    public function printOne(Request $request)
    {
        $id = $request->id;
        $ntahun = $request->year; // date('Y');
        $nbulan = $request->month; // date('n');
        $nbulanini = date('n');
        $nhari = date('w');
        $hari = $this->array_hari[$nhari]['hari']['name'];
        if ($nbulan == 'all') {
            $bulan = 'Semua';
        } else {
            $bulan = $this->array_bulan[$nbulan - 1]['bulan']['name'];
        }
        $bulanini = $this->array_bulan[$nbulanini - 1]['bulan']['name'];

        $datas = ViewLaporanProduksi::where('c1', $id)->get();

        $namafile = $id . '-laporanproduksi_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        session()->put('documents', $namafile);

        if (count($datas) > 0) {
            $pdf = Pdf::loadView('production-order.pdf.lap-prod-one', ['datas' => $datas, 'bulanini' => $bulanini])
                ->setPaper('a4', 'landscape')
                ->setOptions(['enable_php' => true]);

            $output = $pdf->output();
            Storage::disk('pdfs')->put($namafile, $output);

            return response()->json([
                'namafile' => url('documents/' . $namafile . '?v=' . time()),
            ], 200);
        }

        return response()->json([
            'status' => 'Not Found',
        ], 200);
    }
}
