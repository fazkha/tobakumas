<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KritiksaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:200'],
            'tanggal_jawab' => ['nullable'],
            'keterangan_jawab' => ['nullable', 'string', 'max:200'],
        ];
    }
    public function messages()
    {
        return [
            'judul.required' => 'Field -JUDUL- tidak boleh kosong.',
        ];
    }
}
