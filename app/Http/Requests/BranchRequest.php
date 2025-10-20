<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'propinsi_id' => ['required', 'exists:propinsis,id'],
            'kabupaten_id' => ['required', 'exists:kabupatens,id'],
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'kode' => ['required', 'string'],
            'nama' => ['required', 'string', 'max:200'],
            'alamat' => ['nullable', 'string', 'max:200'],
            'kodepos' => ['nullable', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:200'],
            'email' => ['nullable', 'string', 'email', 'max:200'],
        ];
    }
}
