<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'gudang_id' => ['required', 'exists:gudangs,id'],
            'jenis_barang_id' => ['required', 'exists:jenis_barangs,id'],
            'subjenis_barang_id' => ['required', 'exists:subjenis_barangs,id'],
            'nama' => ['required', 'string', 'max:200'],
            'merk' => ['required', 'string', 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:200'],
            'lokasi' => ['nullable', 'string', 'max:200'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,jpg', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Field -NAMA- tidak boleh kosong.',
            'merk.required' => 'Field -MERK- tidak boleh kosong.',
            'jenis_barang_id.required' => 'Field -JENIS BARANG- tidak boleh kosong.',
            'subjenis_barang_id.required' => 'Field -SUB JENIS BARANG- tidak boleh kosong.',
        ];
    }
}
