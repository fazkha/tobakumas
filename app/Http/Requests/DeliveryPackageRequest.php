<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'barang_id' => ['required', 'exists:barangs,id'],
            'satuan_id' => ['required', 'exists:satuans,id'],
            'harga_satuan' => ['required', 'numeric'],
            'kuantiti' => ['required', 'numeric'],
        ];
    }
}
