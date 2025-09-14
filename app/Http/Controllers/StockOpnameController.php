<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\StockOpnameDetail;
use App\Models\ViewPegawaiJabatan;
use App\Http\Requests\StockOpnameRequest;
use App\Http\Requests\StockOpnameUpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class StockOpnameController extends Controller implements HasMiddleware
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
        if (!$request->session()->exists('stock-opname_pp')) {
            $request->session()->put('stock-opname_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('stock-opname_gudang_id')) {
            $request->session()->put('stock-opname_gudang_id', 'all');
        }
        if (!$request->session()->exists('stock-opname_tanggal')) {
            $request->session()->put('stock-opname_tanggal', '_');
        }

        $search_arr = ['stock-opname_gudang_id', 'stock-opname_tanggal'];

        $gudangs = Gudang::where('isactive', 1)->pluck('nama', 'id');
        $datas = StockOpname::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('stock-opname_'));

            if ($search_arr[$i] == 'stock-opname_gudang_id') {
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
        $datas = $datas->latest()->paginate(session('stock-opname_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('stock-opname.index', compact(['datas', 'gudangs']))->with('i', (request()->input('page', 1) - 1) * session('stock-opname_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('stock-opname_pp', $request->pp);
        $request->session()->put('stock-opname_gudang_id', $request->gudang);
        $request->session()->put('stock-opname_tanggal', $request->tanggal);

        $search_arr = ['stock-opname_gudang_id', 'stock-opname_tanggal'];

        $gudangs = Gudang::where('isactive', 1)->pluck('nama', 'id');
        $datas = StockOpname::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('stock-opname_'));

            if ($search_arr[$i] == 'stock-opname_gudang_id') {
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
        $datas = $datas->latest()->paginate(session('stock-opname_pp'));

        $datas->withPath('/warehouse/stock-opname'); // pagination url to

        $view = view('stock-opname.partials.table', compact(['datas', 'gudangs']))->with('i', (request()->input('page', 1) - 1) * session('stock-opname_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $gudangs = Gudang::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        // islevel = 7 = staff; islevel = 3 = kepala divisi
        $petugas = ViewPegawaiJabatan::where('islevel', 7)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');
        $petugas2 = ViewPegawaiJabatan::where('islevel', 3)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');

        return view('stock-opname.create', compact(['gudangs', 'petugas', 'petugas2', 'branch_id']));
    }

    public function store(StockOpnameRequest $request)
    {
        if ($request->validated()) {
            $stock = StockOpname::create([
                'branch_id' => $request->branch_id,
                'gudang_id' => $request->gudang_id,
                'tanggal' => $request->tanggal,
                'petugas_1_id' => $request->petugas_1_id,
                'petugas_2_id' => $request->petugas_2_id,
                'tanggungjawab_id' => $request->tanggungjawab_id,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
                'approved' => (config('custom.stockopname_approval') == false) ? 1 : 0,
                'approved_by' => (config('custom.stockopname_approval') == false) ? 'system' : NULL,
                'approved_at' => (config('custom.stockopname_approval') == false) ? date('Y-m-d H:i:s') : NULL,
            ]);

            return redirect()->route('stock-opname.edit', Crypt::encrypt($stock->id));
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
        }
    }

    public function show(Request $request): View
    {
        $datas = StockOpname::find(Crypt::decrypt($request->stock_opname));
        $details = StockOpnameDetail::where('stock_opname_id', Crypt::decrypt($request->stock_opname))->get();

        if (session('documents')) {
            $namafile = session('documents');
        } else {
            $namafile = Crypt::decrypt($request->stock_opname) . '-stockopname_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        }

        return view('stock-opname.show', compact(['datas', 'details']))->with('documents', $namafile);
    }

    public function edit(Request $request): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $datas = StockOpname::find(Crypt::decrypt($request->stock_opname));
        $details = StockOpnameDetail::where('stock_opname_id', Crypt::decrypt($request->stock_opname))->get();

        Gate::authorize('update', $datas);

        $gudangs = Gudang::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $barangs = Barang::where('branch_id', $branch_id)->where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        // islevel = 7 = staff; islevel = 3 = kepala divisi
        $petugas = ViewPegawaiJabatan::where('islevel', 7)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');
        $petugas2 = ViewPegawaiJabatan::where('islevel', 3)->where('kode_branch', 'PST')->orderBy('nama_plus')->pluck('nama_plus', 'pegawai_id');
        $satuans = Satuan::where('isactive', 1)->orderBy('singkatan')->pluck('singkatan', 'id');

        if (session('documents')) {
            $namafile = session('documents');
        } else {
            $namafile = Crypt::decrypt($request->stock_opname) . '-stockopname_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        }
        $print = false;

        return view('stock-opname.edit', compact(['datas', 'details', 'gudangs', 'barangs', 'satuans', 'petugas', 'petugas2', 'branch_id']))->with('print', $print)->with('documents', $namafile);
    }

    public function update(StockOpnameUpdateRequest $request): RedirectResponse
    {
        $stock = StockOpname::find(Crypt::decrypt($request->stock_opname));

        if ($request->validated()) {
            $stock->update([
                'gudang_id' => $request->gudang_id,
                'tanggal' => $request->tanggal,
                'petugas_1_id' => $request->petugas_1_id,
                'petugas_2_id' => $request->petugas_2_id,
                'tanggungjawab_id' => $request->tanggungjawab_id,
                'keterangan' => $request->keterangan,
                'approved' => (config('custom.stockopname_approval') == false) ? 1 : ($request->approved == 'on' ? 1 : 0),
                'approved_by' => (config('custom.stockopname_approval') == false) ? 'system' : ($request->approved == 'on' ? auth()->user()->email : NULL),
                'approved_at' => (config('custom.stockopname_approval') == false) ? date('Y-m-d H:i:s') : ($request->approved == 'on' ? date('Y-m-d H:i:s') : NULL),
                'updated_by' => auth()->user()->email,
            ]);

            if ($request->approved == 'on') {
                return redirect()->route('stock-opname.index')->with('success', __('messages.successupdated') . ' 👉 ' . $request->tanggal);
            } else {
                return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->tanggal);
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $datas = StockOpname::find(Crypt::decrypt($request->stock_opname));

        Gate::authorize('delete', $datas);

        $details = StockOpnameDetail::where('stock_opname_id', Crypt::decrypt($request->stock_opname))->get();

        return view('stock-opname.delete', compact(['datas', 'details']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $stock = StockOpname::find(Crypt::decrypt($request->stock_opname));

        try {
            $stock->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('stock-opname.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('stock-opname.index')->with('error', $e->getMessage());
        }

        return redirect()->route('stock-opname.index')->with('success', __('messages.successdeleted') . ' 👉 ' . $stock->tanggal);
    }

    public function storeDetail(Request $request)
    {
        $master_id = $request->detail;

        $detail = StockOpnameDetail::create([
            'stock_opname_id' => $master_id,
            'branch_id' => $request->branch_id,
            'barang_id' => $request->barang_id,
            'satuan_id' => $request->satuan_id,
            'stock' => $request->stock,
            'minstock' => $request->minstock,
            'keterangan' => $request->keterangan,
            'selisih_stock' => $request->selisih_stock,
            'selisih_satuan_id' => $request->selisih_satuan_id,
            'adjust_stock' => $request->adjust_stock,
            'adjust_satuan_id' => $request->adjust_satuan_id,
            'created_by' => auth()->user()->email,
            'updated_by' => auth()->user()->email,
        ]);
        // 'before_stock' => $request->before_stock,
        // 'before_satuan_id' => $request->before_satuan_id,
        // 'adjust_by' => $request->adjust_stock ? auth()->user()->email : NULL,
        // 'adjust_at' => $request->adjust_stock ? date('Y-m-d H:i:s') : NULL,
        // 'adjust_harga' => $request->adjust_harga, ===> otomatis dari trigger trg_stock_opname_details_before_insert

        $details = StockOpnameDetail::where('stock_opname_id', $master_id)->get();
        $viewMode = false;

        $view = view('stock-opname.partials.details', compact(['details', 'viewMode']))->render();

        return response()->json([
            'view' => $view,
        ], 200);
    }

    public function deleteDetail(Request $request): JsonResponse
    {
        $detail = StockOpnameDetail::find($request->detail);
        $master = StockOpname::where('id', $detail->stock_opname_id)->get();

        $master_id = $detail->stock_opname_id;
        $view = [];

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'Not Found'], 404);
        }

        $details = StockOpnameDetail::where('stock_opname_id', $master_id)->get();
        $viewMode = false;

        if ($details->count() > 0) {
            $view = view('stock-opname.partials.details', compact(['details', 'viewMode']))->render();
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

    public function print(Request $request)
    {
        $id = Crypt::decrypt($request->stock_opname);
        $datas = StockOpname::find($id);
        $details = StockOpnameDetail::where('stock_opname_id', $id)->get();

        $namafile = $id . '-stockopname_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        session()->put('documents', $namafile);

        if ($datas) {
            $print = true;
            $nhari = date('w', strtotime($datas->tanggal));
            $nbulan = date('n', strtotime($datas->tanggal)) - 1;
            $nbulanini = date('n') - 1;
            $hari = $this->array_hari[$nhari]['hari']['name'];
            $bulan = $this->array_bulan[$nbulan]['bulan']['name'];
            $bulanini = $this->array_bulan[$nbulanini]['bulan']['name'];

            Pdf::view('stock-opname.pdf.perhitungan', ['datas' => $datas, 'details' => $details, 'bulanini' => $bulanini])
                ->orientation(Orientation::Landscape)
                ->margins(3, 0.5, 1, 0.5, Unit::Centimeter)
                ->headerView('stock-opname.pdf.perhitungan-header', ['datas' => $datas, 'hari' => $hari, 'bulan' => $bulan])
                ->footerView('stock-opname.pdf.perhitungan-footer')
                ->format(Format::A4)
                ->disk('pdfs')
                ->save($namafile);

            return response()->json([
                'namafile' => url('documents/' . $namafile),
            ], 200);
        }

        return response()->json([
            'status' => 'Not Found',
        ], 200);
    }
}
