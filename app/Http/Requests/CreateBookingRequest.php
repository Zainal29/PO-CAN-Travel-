<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'route_id' => [
                'required',
                'integer',
                'exists:routes,id',
            ],

            'passengers' => [
                'required',
                'array',
                'min:1',
                'max:10',
            ],

            'passengers.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'passengers.*.phone' => [
                'required',
                'string',
                'max:20',
            ],

            'passengers.*.email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'passengers.*.seat_number' => [
                'required',
                'string',
                'max:10',
            ],
        ];
    }
}