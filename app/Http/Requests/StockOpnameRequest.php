<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockOpnameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'gudang_id' => ['required', 'exists:gudangs,id'],
            'tanggal' => ['nullable', 'date'],
            'petugas_1_id' => ['nullable'],
            'petugas_2_id' => ['nullable'],
            'tanggungjawab_id' => ['nullable'],
            'keterangan' => ['nullable'],
        ];
    }
}
