<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin_city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'destination_city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'departure_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            'passengers' => [
                'nullable',
                'integer',
                'min:1',
                'max:10',
            ],

            'bus_type' => [
                'nullable',
                Rule::in([
                    'economy',
                    'executive',
                    'vip',
                    'super_vip',
                ]),
            ],

            'departure_period' => [
                'nullable',
                Rule::in([
                    'morning',
                    'afternoon',
                    'evening',
                    'night',
                ]),
            ],

            'max_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'sort_by' => [
                'nullable',
                Rule::in([
                    'price_asc',
                    'price_desc',
                    'departure_earliest',
                    'departure_latest',
                ]),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $origin = trim((string) $this->input('origin_city'));
            $destination = trim((string) $this->input('destination_city'));

            if (
                $origin !== '' &&
                $destination !== '' &&
                strcasecmp($origin, $destination) === 0
            ) {
                $validator->errors()->add(
                    'destination_city',
                    'Kota asal dan tujuan tidak boleh sama.'
                );
            }
        });
    }
}
