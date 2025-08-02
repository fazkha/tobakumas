<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleOrderDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sale_order_id' => ['required', 'exists:sale_orders,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'barang_id' => ['required', 'exists:barangs,id'],
            'kuantiti' => ['required', 'min:1'],
            'satuan_id' => ['required', 'exists:satuans,id'],
            'harga_satuan' => ['required', 'min:1'],
            'keterangan' => ['nullable', 'max:200'],
            'pajak' => ['nullable', 'min:0'],
        ];
    }
}
