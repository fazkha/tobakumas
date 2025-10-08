<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'customer_group_id' => ['required', 'exists:customer_groups,id'],
            'propinsi_id' => ['required', 'exists:propinsis,id'],
            'kabupaten_id' => ['required', 'exists:kabupatens,id'],
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'kode' => ['required', 'string', 'min:3', 'max:200'],
            'nama' => ['required', 'string', 'max:200'],
            'alamat' => ['required', 'string', 'max:200'],
            'tanggal_gabung' => ['nullable', 'date'],
            'kontak_nama' => ['required', 'string', 'max:200'],
            'kontak_telpon' => ['required', 'string', 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ];
    }
}
