<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeOutgoodsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kuantiti_outgoods' => ['required', 'numeric', 'min:0'],
            'harga_satuan_outgoods' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'kuantiti_outgoods.required' => 'Field -KUANTITAS- tidak boleh kosong.',
            'kuantiti_outgoods.numeric' => 'Field -KUANTITAS- harus berupa bilangan.',
        ];
    }
}
