<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JabatanPegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'division_id' => ['required', 'exists:divisions,id'],
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
        ];
    }
}
