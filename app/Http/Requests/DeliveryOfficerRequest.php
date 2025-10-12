<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryOfficerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'tanggal' => ['required', 'date'],
            'no_order' => ['nullable', 'string', 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ];
    }
}
