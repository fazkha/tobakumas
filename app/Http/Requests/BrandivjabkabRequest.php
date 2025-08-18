<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandivjabkabRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brandivjab_id' => ['required', 'exists:brandivjabs,id'],
            'propinsi_id' => ['required', 'exists:propinsis,id'],
            'kabupaten_id' => ['required', 'exists:kabupatens,id'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ];
    }
}
