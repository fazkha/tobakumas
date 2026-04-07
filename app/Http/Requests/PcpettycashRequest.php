<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PcpettycashRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'tanggal' => ['required', 'date'],
            'nominal' => ['required', 'numeric'],
        ];
    }
}
