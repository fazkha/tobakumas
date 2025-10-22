<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiGajiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pegawai_id' => ['required', 'integer', 'exists:pegawais,id'],
            't1_keterangan' => ['nullable', 'string', 'max:50'],
            't2_keterangan' => ['nullable', 'string', 'max:50'],
            't3_keterangan' => ['nullable', 'string', 'max:50'],
            'rek_nama_bank' => ['nullable', 'string', 'max:50'],
            'rek_nomor' => ['nullable', 'string', 'max:50'],
            'rek_nama_pemilik' => ['nullable', 'string', 'max:50'],
        ];
    }
}
