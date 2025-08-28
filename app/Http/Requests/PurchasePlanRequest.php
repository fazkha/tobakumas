<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchasePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'periode_bulan' => ['required', 'min:1', 'max:12'],
            'periode_tahun' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'Field -PENYUPLAI- tidak boleh kosong.',
        ];
    }
}
