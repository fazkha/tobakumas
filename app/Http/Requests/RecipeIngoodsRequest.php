<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeIngoodsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kuantiti_ingoods' => ['required', 'numeric', 'min:1'],
            'harga_satuan_ingoods' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'kuantiti_ingoods.required' => 'Field -KUANTITAS- tidak boleh kosong.',
            'kuantiti_ingoods.numeric' => 'Field -KUANTITAS- harus berupa bilangan.',
        ];
    }
}
