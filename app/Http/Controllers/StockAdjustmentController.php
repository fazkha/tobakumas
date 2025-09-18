<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\Gudang;
use App\Models\StockOpnameDetail;
use App\Models\ViewPegawaiJabatan;
use App\Http\Requests\StockAdjustmentRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
// use Spatie\LaravelPdf\Enums\Format;
// use Spatie\LaravelPdf\Enums\Orientation;
// use Spatie\LaravelPdf\Enums\Unit;
// use Spatie\LaravelPdf\Facades\Pdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class StockAdjustmentController extends Controller implements HasMiddleware
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
            new Middleware('permission:stopname-list', only: ['index', 'fetch']),
            new Middleware('permission:stopname-create', only: ['create', 'store']),
            new Middleware('permission:stopname-edit', only: ['edit', 'update']),
            new Middleware('permission:stopname-show', only: ['show']),
            new Middleware('permission:stopname-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('stock-adjustment_pp')) {
            $request->session()->put('stock-adjustment_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('stock-adjustment_gudang_id')) {
            $request->session()->put('stock-adjustment_gudang_id', 'all');
        }
        if (!$request->session()->exists('stock-adjustment_tanggal')) {
            $request->session()->put('stock-adjustment_tanggal', '_');
        }

        $search_arr = ['stock-adjustment_gudang_id', 'stock-adjustment_tanggal'];

        $gudangs = Gudang::where('isactive', 1)->pluck('nama', 'id');
        $datas = StockOpname::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('stock-adjustment_'));

            if ($search_arr[$i] == 'stock-adjustment_gudang_id') {
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

        $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        $datas = $datas->has('stock_opname_details');
        $datas = $datas->latest()->paginate(session('stock-adjustment_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('stock-adjustment.index', compact(['datas', 'gudangs']))->with('i', (request()->input('page', 1) - 1) * session('stock-adjustment_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('stock-adjustment_pp', $request->pp);
        $request->session()->put('stock-adjustment_gudang_id', $request->gudang);
        $request->session()->put('stock-adjustment_tanggal', $request->tanggal);

        $search_arr = ['stock-adjustment_gudang_id', 'stock-adjustment_tanggal'];

        $gudangs = Gudang::where('isactive', 1)->pluck('nama', 'id');
        $datas = StockOpname::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('stock-adjustment_'));

            if ($search_arr[$i] == 'stock-adjustment_gudang_id') {
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

        $datas = $datas->where('branch_id', auth()->user()->profile->branch_id);
        $datas = $datas->has('stock_opname_details');
        $datas = $datas->latest()->paginate(session('stock-adjustment_pp'));

        $datas->withPath('/warehouse/stock-adjustment'); // pagination url to

        $view = view('stock-adjustment.partials.table', compact(['datas', 'gudangs']))->with('i', (request()->input('page', 1) - 1) * session('stock-adjustment_pp'))->render();

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
        $datas = StockOpname::find(Crypt::decrypt($request->stock_adjustment));
        $details = StockOpnameDetail::where('stock_opname_id', Crypt::decrypt($request->stock_adjustment))->get();

        if (session('documents')) {
            $namafile = session('documents');
        } else {
            $namafile = Crypt::decrypt($request->stock_adjustment) . '-stockadjustment_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        }

        return view('stock-adjustment.show', compact(['datas', 'details']))->with('documents', $namafile);
    }

    public function edit(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = StockOpname::find(Crypt::decrypt($request->stock_adjustment));
        $details = StockOpnameDetail::where('stock_opname_id', Crypt::decrypt($request->stock_adjustment))->get();

        Gate::authorize('update', $datas);

        // islevel = 7 = staff; islevel = 3 = kepala divisi
        $petugas = ViewPegawaiJabatan::where('islevel', 7)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');
        $petugas2 = ViewPegawaiJabatan::where('islevel', 3)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');

        if (session('documents')) {
            $namafile = session('documents');
        } else {
            $namafile = Crypt::decrypt($request->stock_adjustment) . '-stockadjustment_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        }
        $print = false;

        return view('stock-adjustment.edit', compact(['datas', 'details', 'petugas', 'petugas2', 'branch_id']))->with('print', $print)->with('documents', $namafile);
    }

    public function update(StockAdjustmentRequest $request): RedirectResponse
    {
        $stock = StockOpname::find(Crypt::decrypt($request->stock_adjustment));

        if ($request->validated()) {
            $stock->update([
                'tanggal_adjustment' => $request->tanggal_adjustment,
                'keterangan_adjustment' => $request->keterangan_adjustment,
                'petugas_1_id' => $request->petugas_1_id,
                'petugas_2_id' => $request->petugas_2_id,
                'adjusted' => (config('custom.stockopname_approval') == false) ? 1 : ($request->adjusted == 'on' ? 1 : 0),
                'adjusted_by' => (config('custom.stockopname_approval') == false) ? 'system' : ($request->adjusted == 'on' ? auth()->user()->email : NULL),
                'adjusted_at' => (config('custom.stockopname_approval') == false) ? date('Y-m-d H:i:s') : ($request->adjusted == 'on' ? date('Y-m-d H:i:s') : NULL),
                'updated_by' => auth()->user()->email,
            ]);

            if ($request->adjusted == 'on') {
                return redirect()->route('stock-adjustment.index')->with('success', __('messages.successupdated') . ' 👉 ' . $request->tanggal_adjustment);
            } else {
                return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->tanggal_adjustment);
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function destroy(Request $request)
    {
        //
    }

    public function updateDetail(Request $request)
    {
        $master_id = $request->detail;
        $stocks = $request->input('stocks');

        foreach ($stocks as $stock) {
            $detail = StockOpnameDetail::where('stock_opname_id', $master_id)->where('id', $stock['id']);

            $detail->update([
                'keterangan_adjustment' => $stock['keterangan_adjustment'],
                'adjust_stock' => $stock['adjust_stock'],
                'adjust_by' => $stock['adjust_stock'] ? auth()->user()->email : NULL,
                'adjust_at' => $stock['adjust_stock'] ? date('Y-m-d H:i:s') : NULL,
            ]);
        }

        $details = StockOpnameDetail::where('stock_opname_id', $master_id)->get();
        $viewMode = false;

        $view = view('stock-adjustment.partials.details', compact(['details', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
        ], 200);
    }

    public function print(Request $request)
    {
        $id = Crypt::decrypt($request->stock_adjustment);
        $datas = StockOpname::find($id);
        $details = StockOpnameDetail::where('stock_opname_id', $id)->where('adjust_stock', '<>', '0')->get();

        $namafile = $id . '-stockadjustment_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        session()->put('documents', $namafile);

        if ($datas) {
            $print = true;
            $nhari = date('w', strtotime($datas->tanggal_adjustment));
            $nbulan = date('n', strtotime($datas->tanggal_adjustment)) - 1;
            $nbulanini = date('n') - 1;
            $hari = $this->array_hari[$nhari]['hari']['name'];
            $bulan = $this->array_bulan[$nbulan]['bulan']['name'];
            $bulanini = $this->array_bulan[$nbulanini]['bulan']['name'];

            $pdf = Pdf::loadView('stock-adjustment.pdf.penyesuaian', ['datas' => $datas, 'details' => $details, 'hari' => $hari, 'bulan' => $bulan, 'bulanini' => $bulanini])
                ->setPaper('a4', 'landscape')
                ->setOptions(['enable_php' => true]);

            $output = $pdf->output();
            Storage::disk('pdfs')->put($namafile, $output);

            return response()->json([
                'namafile' => url('documents/' . $namafile),
            ], 200);
        }

        return response()->json([
            'status' => 'Not Found',
        ], 200);
    }
}
