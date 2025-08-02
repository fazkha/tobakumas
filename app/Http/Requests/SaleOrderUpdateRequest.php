<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleOrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'tanggal' => ['nullable', 'date'],
            'no_order' => ['nullable', 'string', 'max:200'],
            'total_harga' => ['nullable'],
            'biaya_angkutan' => ['nullable'],
            'pajak' => ['nullable'],
        ];
    }
}
