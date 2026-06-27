<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    public function orderMitra(Request $request)
    {
        $order = DB::table('sale_orders as s1')
            ->join('customers as c1', function ($join) {
                $join->on('c1.branch_link_id', '=', 's1.branch_id')
                    ->on('c1.id', '=', 's1.customer_id');
            })
            ->join('sale_order_mitras as s2', 's2.sale_order_id', '=', 's1.id')
            ->select('s2.kuantiti')
            ->where('s1.branch_id', $request->branch_id)
            ->where('s1.tanggal', $request->tanggal)
            ->where('s2.gerobak_id', $request->gerobak_id)
            ->where('s1.isactive', 1)
            ->where('c1.isactive', 1)
            ->first();

        return [
            'status' => 'success',
            'order' => $order
        ];
    }

    public function barangList(Request $request)
    {
        $barang = Barang::join('jenis_barangs', 'jenis_barangs.id', '=', 'barangs.jenis_barang_id')
            ->join('satuans', 'satuans.id', '=', 'barangs.satuan_jual_id')
            ->where('barangs.isactive', 1)
            ->where(function ($q) {
                $q->whereIn('barangs.jenis_barang_id', [2, 4, 6, 7, 8, 9, 10])
                    ->orWhere('barangs.nama', 'like', '%gula pasir%');
            })
            ->orderBy('jenis_barangs.nama')
            ->orderBy('barangs.nama')
            ->selectRaw('barangs.id, barangs.nama as name, jenis_barangs.nama as kelompok, satuans.singkatan as satuan, barangs.stock')
            ->get()
            ->toJson();

        return [
            'status' => 'success',
            'barang' => $barang
        ];
    }

    public function customerInternal(Request $request)
    {
        $customer = Customer::where('branch_link_id', $request->id)->select('id')->first();

        return [
            'status' => 'success',
            'customer' => $customer
        ];
    }
}
