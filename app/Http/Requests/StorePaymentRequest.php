<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'payment_method' => [
                'required',
                'in:bca,bri,qris',
            ],

            'payer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'payer_phone' => [
                'required',
                'string',
                'max:20',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'payer_name' => trim((string) $this->input('payer_name')),
            'payer_phone' => trim((string) $this->input('payer_phone')),
        ]);
    }
}