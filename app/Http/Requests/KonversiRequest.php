<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KonversiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'satuan_id' => ['required', 'exists:satuans,id'],
            'satuan2_id' => ['required', 'exists:satuans,id'],
            'operator' => ['required'],
            'bilangan' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'satuan_id.required' => 'Field -SATUAN- tidak boleh kosong.',
            'satuan2_id.required' => 'Field -SATUAN- tidak boleh kosong.',
        ];
    }
}
