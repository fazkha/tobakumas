<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purchase_order_id' => ['required', 'exists:purchase_orders,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'barang_id' => ['required', 'exists:barangs,id'],
            'satuan_id' => ['required', 'exists:satuans,id'],
            'kuantiti' => ['required', 'min:1'],
            'harga_satuan' => ['required', 'min:1'],
            'keterangan' => ['nullable', 'max:200'],
            'pajak' => ['nullable', 'min:0'],
        ];
    }
}
