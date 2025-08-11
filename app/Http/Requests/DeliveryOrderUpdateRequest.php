<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryOrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal' => ['date'],
            'alamat' => ['string', 'max:200'],
            'keterangan' => ['string', 'max:200'],
        ];
    }
}
