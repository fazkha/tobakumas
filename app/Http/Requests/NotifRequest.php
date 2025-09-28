<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotifRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:65500']
        ];
    }
}
