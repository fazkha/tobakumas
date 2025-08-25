<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keterangan_terima' => ['nullable', 'string', 'max:200'],
            'items.*.keterangan_terima' => ['nullable', 'string', 'max:200'],
        ];
    }
}
