<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandivjabpegRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brandivjab_id' => ['required', 'exists:brandivjabs,id'],
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'tanggal_mulai' => ['nullable'],
            'tanggal_akhir' => ['nullable'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ];
    }
}
