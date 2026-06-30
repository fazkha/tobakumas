<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Models\SaleOrderMitra;
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

    public function orderCabangGet(Request $request)
    {
        $order = DB::select("CALL sp_order_pc_id(?)", [$request->pc_email]);

        return [
            'status' => 'success',
            'order' => $order
        ];
    }

    public function orderCabangDelete(Request $request)
    {
        if ($request->grup == 1) {
            $detail = SaleOrderDetail::where('id', $request->id)->first();
        } else {
            $detail = SaleOrderMitra::where('id', $request->id)->first();
        }

        try {
            $detail->delete();
        } catch (\Illuminate\Database\QueryException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        $order = DB::select("CALL sp_order_pc_id(?)", [$request->pc_email]);

        return [
            'status' => 'success',
            'order' => $order
        ];
    }

    public function orderCabangReceive(Request $request)
    {
        if ($request->grup == 1) {
            $detail = SaleOrderDetail::where('id', $request->detilorder_id)->first();
        } else {
            $detail = SaleOrderMitra::where('id', $request->detilorder_id)->first();
        }

        try {
            $detail->update([
                'cust_received' => $request->checked,
                'cust_note' => $request->keterangan,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        $order = DB::select("CALL sp_order_pc_id(?)", [$request->pc_email]);

        return [
            'status' => 'success',
            'order' => $order
        ];
    }

    public function orderCabang(Request $request)
    {
        $produst_id = 1;
        $order = null;
        $customer = Customer::where('branch_link_id', $request->cabang_id)->select('id')->first();

        if ($customer) {
            $master = SaleOrder::where('customer_id', $customer->id)
                ->where('branch_id', $request->cabang_id)
                ->where('hke', $request->hke)
                ->where('tanggal', $request->tanggal)
                ->first();

            if (!$master) {
                $master = SaleOrder::create([
                    'branch_id' => $request->cabang_id,
                    'customer_id' => $customer->id,
                    'product_id' => $produst_id,
                    'hke' => $request->hke,
                    'tanggal' => $request->tanggal,
                    'tunai' => 1,
                    'isactive' => 1,
                    'created_by' => $request->pc_email,
                    'updated_by' => $request->pc_email,
                    'approved' => 1,
                    'approved_by' => $request->pc_email,
                    'approved_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $barang = Barang::where('id', $request->barang)
                ->where('isactive', 1)
                ->first();

            if ($barang) {
                if ($request->gerobak) {
                    $detail_barang = SaleOrderMitra::where('sale_order_id', $master->id)
                        ->where('branch_id', $request->cabang_id)
                        ->where('barang_id', $request->barang)
                        ->where('gerobak_id', $request->gerobak)
                        ->first();

                    if ($detail_barang) {
                        $detail_barang->update([
                            'kuantiti' => $request->kuantiti,
                            'harga_satuan' => $barang->harga_satuan_jual,
                            'keterangan' => $request->keterangan,
                            'updated_by' => $request->pc_email,
                        ]);
                    } else {
                        $detail_barang = SaleOrderMitra::create([
                            'sale_order_id' => $master->id,
                            'branch_id' => $request->cabang_id,
                            'gerobak_id' => $request->gerobak,
                            'barang_id' => $request->barang,
                            'satuan_id' => $barang->satuan_jual_id,
                            'kuantiti' => $request->kuantiti,
                            'pajak' => 0,
                            'harga_satuan' => $barang->harga_satuan_jual,
                            'keterangan' => $request->keterangan,
                            'created_by' => $request->pc_email,
                            'updated_by' => $request->pc_email,
                            'approved' => 1,
                            'approved_by' => $request->pc_email,
                            'approved_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                } else {
                    $detail_barang = SaleOrderDetail::where('sale_order_id', $master->id)
                        ->where('branch_id', $request->cabang_id)
                        ->where('barang_id', $request->barang)
                        ->first();

                    if ($detail_barang) {
                        $detail_barang->update([
                            'kuantiti' => $request->kuantiti,
                            'harga_satuan' => $barang->harga_satuan_jual,
                            'keterangan' => $request->keterangan,
                            'updated_by' => $request->pc_email,
                        ]);
                    } else {
                        $detail_barang = SaleOrderDetail::create([
                            'sale_order_id' => $master->id,
                            'branch_id' => $request->cabang_id,
                            'barang_id' => $request->barang,
                            'satuan_id' => $barang->satuan_jual_id,
                            'kuantiti' => $request->kuantiti,
                            'stock' => $barang->stock,
                            'pajak' => 0,
                            'harga_satuan' => $barang->harga_satuan_jual,
                            'keterangan' => $request->keterangan,
                            'created_by' => $request->pc_email,
                            'updated_by' => $request->pc_email,
                            'approved' => 1,
                            'approved_by' => $request->pc_email,
                            'approved_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }

        $order = DB::select("CALL sp_order_pc_id(?)", [$request->pc_email]);

        return [
            'status' => 'success',
            'order' => $order
        ];
    }

    public function modalCabang(Request $request)
    {
        $modal = DB::select("CALL sp_modal_periode_cabang(?,?,?)", [$request->start_date, $request->end_date, $request->branch_id]);

        // if ($modal[0]->branch_id == null) {
        //     $modal = [[
        //         "branch_id" => $request->branch_id,
        //         "total_modal" => 0
        //     ]];
        // }

        return [
            'status' => 'success',
            'modal' =>  $modal,
        ];
    }
}
