<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SatuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'singkatan' => ['required', 'string', 'max:50'],
            'nama_lengkap' => ['required', 'string', 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function messages()
    {
        return [
            'singkatan.required' => 'Field -ABBREVIATION- tidak boleh kosong.',
            'nama_lengkap.required' => 'Field -FULL NAME- tidak boleh kosong.',
        ];
    }
}
