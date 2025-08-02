<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'kode' => ['required', 'string', 'min:3', 'max:3'],
            'nama' => ['required', 'string', 'max:200'],
            'alamat' => ['required', 'string', 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ];
    }
}
