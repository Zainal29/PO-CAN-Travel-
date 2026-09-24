<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bus_id' => [
                'required',
                'integer',
                'exists:buses,id',
            ],

            'origin_city' => [
                'required',
                'string',
                'max:100',
            ],

            'origin_terminal' => [
                'required',
                'string',
                'max:150',
            ],

            'destination_city' => [
                'required',
                'string',
                'max:100',
            ],

            'destination_terminal' => [
                'required',
                'string',
                'max:150',
            ],

            'departure_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'departure_time' => [
                'required',
                'date_format:H:i',
            ],

            'estimated_arrival_time' => [
                'required',
                'date_format:H:i',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'available_seats' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'full',
                    'cancelled',
                ]),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (
                $this->filled('origin_city') &&
                $this->filled('destination_city') &&
                strcasecmp(
                    trim($this->origin_city),
                    trim($this->destination_city)
                ) === 0
            ) {
                $validator->errors()->add(
                    'destination_city',
                    'Kota asal dan kota tujuan tidak boleh sama.'
                );
            }

            if (
                $this->filled('available_seats') &&
                $this->filled('bus_id')
            ) {
                $bus = \App\Models\Bus::find($this->bus_id);

                if ($bus && $this->available_seats > $bus->total_seats) {
                    $validator->errors()->add(
                        'available_seats',
                        'Jumlah kursi tersedia tidak boleh melebihi kapasitas bus.'
                    );
                }
            }
        });
    }
}
