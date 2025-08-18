<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandivjabRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'division_id' => ['nullable'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'atasan_id' => ['nullable'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ];
    }
}
