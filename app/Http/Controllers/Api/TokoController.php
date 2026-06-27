<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        dd($order);

        return [
            'status' => 'success',
            'order' => $order
        ];
    }
}
