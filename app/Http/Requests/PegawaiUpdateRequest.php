<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'nama_lengkap' => ['required', 'string', 'max:200'],
            'alamat_tinggal' => ['required', 'string', 'max:200'],
            'telpon' => ['required', 'max:50'],
            'kelamin' => ['required'],
        ];
    }
}
