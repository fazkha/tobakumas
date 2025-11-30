<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleOrderMitraRequest extends FormRequest
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
            'satuan_id' => ['required', 'exists:satuans,id'],
            'kuantiti' => ['required', 'min:1'],
            'harga_satuan' => ['required', 'min:1'],
            'keterangan' => ['nullable', 'max:200'],
            'pajak' => ['nullable', 'min:0'],
            'gerobak_id' => [
                'required',
                Rule::unique('sale_order_mitras')->where(function ($query) {
                    return $query->where('gerobak_id', $this->gerobak_id)
                        ->where('sale_order_id', $this->sale_order_id);
                }),
            ],
        ];
    }
}
