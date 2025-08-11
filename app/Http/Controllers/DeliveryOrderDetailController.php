<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrderDetail;
use Illuminate\Http\Request;

class DeliveryOrderDetailController extends Controller
{
    public function update(Request $request)
    {
        $details = $request->input('detail_id');
        $pakets = $request->input('paket_id');
        $barangs = $request->input('barang_id');
        $satuans = $request->input('satuan_id');
        $kuantitis = $request->input('kuantiti');
        $i = 0;

        foreach ($details as $detail) {
            $delivery = DeliveryOrderDetail::find($detail);
            // echo $pakets[$i];

            $delivery->update([
                'paket_id' => $pakets[$i] ? $pakets[$i] : NULL,
                // 'barang_id' => $barangs[$i] ? $barangs[$i] : NULL,
                // 'satuan_id' => $satuans[$i] ? $satuans[$i] : NULL,
                // 'kuantiti' => $kuantitis[$i] ? $kuantitis[$i] : NULL,
            ]);

            $i++;
        }

        return redirect()->back()->with('success', __('messages.successupdated') . ' 👉 ' . $delivery->delivery_order->alamat);
    }
}
