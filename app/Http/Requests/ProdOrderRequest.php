<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sale_order_id' => ['required', 'exists:sale_orders,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }
}
