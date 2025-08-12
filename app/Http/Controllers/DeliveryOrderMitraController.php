<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrderMitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DeliveryOrderMitraController extends Controller
{
    public function update(Request $request)
    {
        $mitras = $request->input('mitra_id');
        $pakets = $request->input('mitra_paket_id');
        $barangs = $request->input('mitra_barang_id');
        $satuans = $request->input('mitra_satuan_id');
        $kuantitis = $request->input('mitra_kuantiti');
        $i = 0;

        foreach ($mitras as $mitra) {
            $delivery = DeliveryOrderMitra::find($mitra);
            // echo $pakets[$i];

            $delivery->update([
                'paket_id' => $pakets[$i] ? $pakets[$i] : NULL,
                'barang_id' => $barangs[$i] ? $barangs[$i] : NULL,
                'satuan_id' => $satuans[$i] ? $satuans[$i] : NULL,
                'kuantiti' => $kuantitis[$i] ? $kuantitis[$i] : NULL,
                'updated_by' => auth()->user()->email,
            ]);

            $i++;
        }

        return redirect()->route('delivery-order.edit', Crypt::encrypt($delivery->delivery_order_id));
    }
}
