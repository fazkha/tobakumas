<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Branch;
use App\Models\SaleOrder;
use App\Models\Salelastnumber;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\CustomerGroup;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Propinsi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:customer-list', only: ['index', 'fetch']),
            new Middleware('permission:customer-create', only: ['create', 'store']),
            new Middleware('permission:customer-edit', only: ['edit', 'update']),
            new Middleware('permission:customer-show', only: ['show']),
            new Middleware('permission:customer-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('customer_pp')) {
            $request->session()->put('customer_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('customer_isactive')) {
            $request->session()->put('customer_isactive', 'all');
        }
        if (!$request->session()->exists('customer_customer_group_id')) {
            $request->session()->put('customer_customer_group_id', 'all');
        }
        if (!$request->session()->exists('customer_kode')) {
            $request->session()->put('customer_kode', '_');
        }
        if (!$request->session()->exists('customer_nama')) {
            $request->session()->put('customer_nama', '_');
        }
        if (!$request->session()->exists('customer_alamat')) {
            $request->session()->put('customer_alamat', '_');
        }
        if (!$request->session()->exists('customer_kontak_nama')) {
            $request->session()->put('customer_kontak_nama', '_');
        }
        if (!$request->session()->exists('customer_kontak_telpon')) {
            $request->session()->put('customer_kontak_telpon', '_');
        }

        $search_arr = ['customer_isactive', 'customer_customer_group_id', 'customer_kode', 'customer_nama', 'customer_alamat', 'customer_kontak_telpon', 'customer_kontak_nama'];

        $datas = Customer::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('customer_'));

            if ($search_arr[$i] == 'customer_isactive' || $search_arr[$i] == 'customer_customer_group_id') {
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
        $datas = $datas->latest()->paginate(session('customer_pp'));
        $groups = CustomerGroup::where('isactive', 1)->pluck('nama', 'id');

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('customer.index', compact(['datas', 'groups']))->with('i', (request()->input('page', 1) - 1) * session('customer_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('customer_pp', $request->pp);
        $request->session()->put('customer_isactive', $request->isactive);
        $request->session()->put('customer_customer_group_id', $request->group);
        $request->session()->put('customer_kode', $request->kode);
        $request->session()->put('customer_nama', $request->nama);
        $request->session()->put('customer_alamat', $request->alamat);
        $request->session()->put('customer_kontak_nama', $request->kontak);
        $request->session()->put('customer_kontak_telpon', $request->telpon);

        $search_arr = ['customer_isactive', 'customer_customer_group_id', 'customer_kode', 'customer_nama', 'customer_alamat', 'customer_kontak_telpon', 'customer_kontak_nama'];

        $datas = Customer::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('customer_'));

            if ($search_arr[$i] == 'customer_isactive' || $search_arr[$i] == 'customer_customer_group_id') {
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
        $datas = $datas->latest()->paginate(session('customer_pp'));
        $groups = CustomerGroup::where('isactive', 1)->pluck('nama', 'id');

        $datas->withPath('/sale/customer'); // pagination url to

        $view = view('customer.partials.table', compact(['datas', 'groups']))->with('i', (request()->input('page', 1) - 1) * session('customer_pp'))->render();

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
        $branches = Branch::where('isactive', 1)->where('kode', '!=', main_office_code())->orderby('nama')->pluck('nama', 'id');
        $groups = CustomerGroup::where('isactive', 1)->pluck('nama', 'id');
        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');

        return view('customer.create', compact('branch_id', 'branch', 'branches', 'groups', 'propinsis', 'kabupatens', 'kecamatans'));
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $customer = Customer::create([
                'branch_id' => $request->branch_id,
                'branch_link_id' => $request->branch_link_id,
                'customer_group_id' => $request->customer_group_id,
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
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

            if ($customer) {
                return redirect()->back()->with('success', __('messages.successadded') . ' 👉 ' . $request->nama);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Error occured while saving!');
    }

    public function show(Request $request): View
    {
        $datas = Customer::find(Crypt::decrypt($request->customer));

        return view('customer.show', compact(['datas']));
    }

    public function edit(Request $request): View
    {
        $datas = Customer::find(Crypt::decrypt($request->customer));
        $groups = CustomerGroup::where('isactive', 1)->pluck('nama', 'id');
        $propinsis = Propinsi::where('isactive', 1)->orderBy('nama')->pluck('nama', 'id');
        $kabupatens = Kabupaten::where('isactive', 1)->where('propinsi_id', $datas->propinsi_id)->orderBy('nama')->pluck('nama', 'id');
        $kecamatans = Kecamatan::where('isactive', 1)->where('kabupaten_id', $datas->kabupaten_id)->orderBy('nama')->pluck('nama', 'id');
        $branches = Branch::where('isactive', 1)->where('kode', '!=', main_office_code())->orderby('nama')->pluck('nama', 'id');

        return view('customer.edit', compact(['datas', 'groups', 'branches', 'propinsis', 'kabupatens', 'kecamatans']));
    }

    public function update(CustomerUpdateRequest $request): RedirectResponse
    {
        $customer = Customer::find(Crypt::decrypt($request->customer));

        if ($request->validated()) {

            $customer->update([
                'customer_group_id' => $request->customer_group_id,
                'branch_link_id' => $request->branch_link_id,
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
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
        $customer = Customer::find(Crypt::decrypt($request->customer));

        $datas = $customer;

        return view('customer.delete', compact(['datas']));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $customer = Customer::find(Crypt::decrypt($request->customer));

        try {
            $customer->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return redirect()->route('customer.index')->with('error', 'Integrity constraint violation');
            }
            return redirect()->route('customer.index')->with('error', $e->getMessage());
        }

        return redirect()->route('customer.index')
            ->with('success', __('messages.successdeleted') . ' 👉 ' . $customer->nama);
    }

    public function getCode(Request $request): JsonResponse
    {
        $get = Customer::where('id', $request->id)->first();
        $cus = $get->kode;
        $cab_id = $get->branch_id;
        $cab = $get->branch->kode;

        $t = ($request->tahun !== '_') ? $request->tahun : $t = date('Y');
        $b = ($request->bulan !== '_') ? $request->bulan : $b = date('m');

        // $cnt = SaleOrder::where('customer_id', $request->id)->where('branch_id', $cab_id)->whereRaw('YEAR(tanggal) = ?', $t)->whereRaw('MONTH(tanggal) = ?', $b)->count();
        $cnt = Salelastnumber::where('customer_id', $request->id)->where('branch_id', $cab_id)->where('tahun', $t)->where('bulan', $b)->first();

        if ($cnt && $cnt > 0) {
            $cnt = str_pad(strval($cnt + 1), 3, "0", STR_PAD_LEFT);
        } else {
            $cnt = '001';
        }

        return response()->json([
            'p1' => config('custom.so_prefix') . '/' . $cab . '/' . $cus,
            'p2' => $t,
            'p3' => $b,
            'p4' => $cnt
        ], 200);
    }
}
