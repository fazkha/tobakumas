<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_adjustment' => ['nullable', 'date'],
            'petugas_1_id' => ['nullable'],
            'petugas_2_id' => ['nullable'],
            'keterangan_adjustment' => ['nullable'],
        ];
    }
}
