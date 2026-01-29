<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Models\SaleOrderMitra;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SaleInvoiceController extends Controller implements HasMiddleware
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
        if (!$request->session()->exists('sale-invoice_pp')) {
            $request->session()->put('sale-invoice_pp', config('custom.list_per_page_opt_1'));
        }
        if (!$request->session()->exists('sale-invoice_isactive')) {
            $request->session()->put('sale-invoice_isactive', 'all');
        }
        if (!$request->session()->exists('sale-invoice_tunai')) {
            $request->session()->put('sale-invoice_tunai', 'all');
        }
        if (!$request->session()->exists('sale-invoice_customer_id')) {
            $request->session()->put('sale-invoice_customer_id', 'all');
        }
        if (!$request->session()->exists('sale-invoice_tanggal')) {
            $request->session()->put('sale-invoice_tanggal', '_');
        }
        if (!$request->session()->exists('sale-invoice_no_order')) {
            $request->session()->put('sale-invoice_no_order', '_');
        }

        $search_arr = ['sale-invoice_isactive', 'sale-invoice_tunai', 'sale-invoice_customer_id', 'sale-invoice_no_order', 'sale-invoice_tanggal'];

        // $datas = DB::table('sale-invoices');
        $branch_id = auth()->user()->profile->branch_id;
        $customers = Customer::where('branch_id', $branch_id)->where('isactive', 1)->pluck('nama', 'id');
        $datas = SaleOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('sale-invoice_'));

            if ($search_arr[$i] == 'sale-invoice_isactive' || $search_arr[$i] == 'sale-invoice_tunai' || $search_arr[$i] == 'sale-invoice_customer_id') {
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
        $datas = $datas->latest()->paginate(session('sale-invoice_pp'));

        if (session('documents')) {
            $namafile = session('documents');
        } else {
            $namafile = '_invoice_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        }

        if ($request->page && $datas->count() == 0) {
            return redirect()->route('dashboard');
        }

        return view('sale-invoice.index', compact(['datas', 'customers']))->with('documents', $namafile)->with('i', (request()->input('page', 1) - 1) * session('sale-invoice_pp'));
    }

    public function fetchdb(Request $request): JsonResponse
    {
        $request->session()->put('sale-invoice_pp', $request->pp);
        $request->session()->put('sale-invoice_isactive', $request->isactive);
        $request->session()->put('sale-invoice_tunai', $request->tunai);
        $request->session()->put('sale-invoice_customer_id', $request->customer);
        $request->session()->put('sale-invoice_tanggal', $request->tanggal);
        $request->session()->put('sale-invoice_no_order', $request->no_order);

        $search_arr = ['sale-invoice_isactive', 'sale-invoice_tunai', 'sale-invoice_customer_id', 'sale-invoice_no_order', 'sale-invoice_tanggal'];

        $branch_id = auth()->user()->profile->branch_id;
        $customers = Customer::where('branch_id', $branch_id)->where('isactive', 1)->pluck('nama', 'id');
        $datas = SaleOrder::query();

        for ($i = 0; $i < count($search_arr); $i++) {
            $field = substr($search_arr[$i], strlen('sale-invoice_'));

            if ($search_arr[$i] == 'sale-invoice_isactive' || $search_arr[$i] == 'sale-invoice_tunai' || $search_arr[$i] == 'sale-invoice_customer_id') {
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
        $datas = $datas->latest()->paginate(session('sale-invoice_pp'));

        if (session('documents')) {
            $namafile = session('documents');
        } else {
            $namafile = '_invoice_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        }

        $datas->withPath('/sale/invoice'); // pagination url to

        $view = view('sale-invoice.partials.table', compact(['datas', 'customers']))->with('documents', $namafile)->with('i', (request()->input('page', 1) - 1) * session('sale-invoice_pp'))->render();

        if ($view) {
            return response()->json($view, 200);
        } else {
            return response()->json(null, 400);
        }
    }

    public function printSelected(Request $request)
    {
        $selected = $request->input('isprint');
        $namafile = '_invoice_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        session()->put('documents', $namafile);

        if ($selected) {
            // foreach ($selected as $select) {}
            $pdf = Pdf::loadView('sale-invoice.pdf.multi_invoice', ['selected' => $selected])
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

    public function print(Request $request)
    {
        $id = $request->invoice;
        $datas = SaleOrder::find($id);
        $details = SaleOrderDetail::where('sale_order_id', $id)->orderBy('barang_id')->get();
        // $adonans = SaleOrderMitra::where('sale_order_id', $id)->orderBy('pegawai_id')->orderBy('barang_id')->get();
        $adonans = SaleOrderMitra::where('sale_order_id', $id)->orderBy('gerobak_id')->orderBy('barang_id')->get();
        dd($adonans);

        $total_price = SaleOrderDetail::where('sale_order_id', $id)->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $total_price_adonan = SaleOrderMitra::where('sale_order_id', $id)->select(DB::raw('SUM((harga_satuan * (1 + (pajak/100))) * kuantiti) as total_price'))->value('total_price');
        $totals = [
            'sub_price' => $total_price * 1,
            'sub_price_adonan' => $total_price_adonan * 1,
            'total_price' => $datas->total_harga,
        ];
        $namafile = $datas->id . '-invoice_' . str_replace('@', '(at)', str_replace('.', '_', auth()->user()->email)) . '.pdf';
        session()->put('documents', $namafile);

        if ($datas) {
            $pdf = Pdf::loadView('sale-invoice.pdf.invoice', ['datas' => $datas, 'details' => $details, 'adonans' => $adonans, 'totals' => $totals])
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
