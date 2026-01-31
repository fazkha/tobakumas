<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengumumanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:65000'],
            'lokasi' => ['nullable', 'string', 'max:200'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,jpg', 'max:2048'],
        ];
    }
    public function messages()
    {
        return [
            'judul.required' => 'Field -JUDUL- tidak boleh kosong.',
        ];
    }
}
