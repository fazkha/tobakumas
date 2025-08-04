<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
        ];
    }

    public function messages()
    {
        return [
            'judul.required' => 'Field -NAMA- tidak boleh kosong.',
            'judul.unique' => 'Field -NAMA- sudah dipakai pada data lain.',
        ];
    }
}
