<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'tanggal' => ['nullable', 'date'],
            'no_order' => ['nullable', 'string', 'max:200'],
            'total_harga' => ['nullable'],
            'biaya_angkutan' => ['nullable'],
            'pajak' => ['nullable'],
        ];
    }
}
