<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\Branch;
use App\Models\Purchaselastnumber;
use App\Http\Requests\SupplierRequest;
use App\Http\Requests\SupplierUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:supplier-list', only: ['index', 'fetch']),
            new Middleware('permission:supplier-create', only: ['create', 'store']),
            new Middleware('permission:supplier-edit', only: ['edit', 'update']),
            new Middleware('permission:supplier-show', only: ['show']),
            new Middleware('permission:supplier-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('supplier_pp')) {
            $request->session()->put('supplier_pp', 5);
        }
        if (!$request->session()->exists('supplier_isactive')) {
            $request->session()->put('supplier_isactive', 'all');
        }
        if (!$request->session()->exists('supplier_kode')) {
            $request->session()->put('supplier_kode', '_');
        }
        if (!$request->session()->exists('supplier_nama')) {
            $request->session()->put('supplier_nama', '_');
        }
        if (!$request->session()->exists('supplier_alamat')) {
            $request->session()->put('supplier_alamat', '_');
        }
        if (!$request->session()->exists('supplier_kontak_nama')) {
            $request->session()->put('supplier_kontak_nama', '_');
        }
        if (!$request->session()->exists('supplier_kontak_telpon')) {
            $request->session()->put('supplier_kontak_telpon', '_');
        }

        $search_arr = ['supplier_isactive', 'supplier_kode', 'supplier_nama', 'supplier_alamat', 'supplier_kontak_telpon', 'supplier_kontak_nama'];

        $datas = Supplier::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('supplier_'));

            if ($search_arr[$i] == 'supplier_isactive') {
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
        $datas = $datas->latest()->paginate(session('supplier_pp'));

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('supplier.index', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('supplier_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('supplier_pp', $request->pp);
        $request->session()->put('supplier_isactive', $request->isactive);
        $request->session()->put('supplier_kode', $request->kode);
        $request->session()->put('supplier_nama', $request->nama);
        $request->session()->put('supplier_alamat', $request->alamat);
        $request->session()->put('supplier_kontak_nama', $request->kontak);
        $request->session()->put('supplier_kontak_telpon', $request->telpon);

        $search_arr = ['supplier_isactive', 'supplier_kode', 'supplier_nama', 'supplier_alamat', 'supplier_kontak_telpon', 'supplier_kontak_nama'];

        $datas = Supplier::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('supplier_'));

            if ($search_arr[$i] == 'supplier_isactive') {
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
        $datas = $datas->latest()->paginate(session('supplier_pp'));

        $datas->withPath('/purchase/supplier'); // pagination url to

        $view = view('supplier.partials.table', compact(['datas']))->with('i', (request()->input('page', 1) - 1) * session('supplier_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function create(): View
    {
        $branch_id = auth()->user()->profile->branch_id;
        $branch = Branch::find($branch_id);

        return view('supplier.create', compact('branch_id', 'branch'));
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $supplier = Supplier::create([
                'branch_id' => $request->branch_id,
                'kode' => $request->kode,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'tanggal_gabung' => $request->tanggal_gabung,
                'kontak_nama' => $request->kontak_nama,
                'kontak_telpon' => $request->kontak_telpon,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($supplier) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Supplier::find(Crypt::decrypt($request->supplier));

        return view('supplier.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Supplier::find(Crypt::decrypt($request->supplier));

        return view('supplier.edit', compact(['datas']));
    }

    public function update(SupplierUpdateRequest $request): RedirectResponse
    {
        $supplier = Supplier::find(Crypt::decrypt($request->supplier));

        if ($request->validated()) {

            $supplier->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'tanggal_gabung' => $request->tanggal_gabung,
                'kontak_nama' => $request->kontak_nama,
                'kontak_telpon' => $request->kontak_telpon,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'updated_by' => auth()->user()->email,
            ]);

            return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $request->nama);
        } else {
            return redirect()->back()->withInput()->with('error', 'Error occured while updating!');
        }
    }

    public function delete(Request $request): View
    {
        $supplier = Supplier::find(Crypt::decrypt($request->supplier));
        $datas = $supplier;

        return view('supplier.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $supplier = Supplier::find(Crypt::decrypt($request->supplier));

        try {
            $supplier->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('supplier.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('supplier.index')->with('error', $e->getMessage());
        }

        return redirect()->route('supplier.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $supplier->nama);
    }

    public function getCode(Request $request): JsonResponse
    {
        $get = Supplier::where('id', $request->id)->first();
        $sup = $get->kode;
        $cab_id = $get->branch_id;
        $cab = $get->branch->kode;

        $t = ($request->tahun !== '_') ? $request->tahun : $t = date('Y');
        $b = ($request->bulan !== '_') ? $request->bulan : $b = date('m');

        if (!$get) {
            return response()->json(null, 400);
        }
        if ($request->tahun === '_' || $request->bulan === '_') {
            return response()->json(null, 400);
        }

        // $cnt = PurchaseOrder::where('supplier_id', $request->id)->where('branch_id', $cab_id)->whereRaw('YEAR(tanggal) = ?', $t)->whereRaw('MONTH(tanggal) = ?', $b)->count();
        $cnt = Purchaselastnumber::where('supplier_id', $request->id)->where('branch_id', $cab_id)->where('tahun', $t)->where('bulan', $b)->first();

        if ($cnt) {
            $last = $cnt->last_number;

            if ($last > 0) {
                $last += 1;

                $cnt->update([
                    'last_number' => $last
                ]);
            } else {
                $last = 1;

                Purchaselastnumber::create([
                    'supplier_id' => $request->id,
                    'branch_id' => $cab_id,
                    'tahun' => $t,
                    'bulan' => $b
                ]);
            }

            $cnt = str_pad(strval($last), 3, "0", STR_PAD_LEFT);
        } else {
            $cnt = '001';
        }

        return response()->json([
            'p1' => config('custom.po_prefix') . '/' . $cab . '/' . $sup,
            'p2' => $t,
            'p3' => $b,
            'p4' => $cnt
        ], 200);
    }
}
