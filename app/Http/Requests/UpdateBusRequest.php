<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! is_string($this->input('facilities'))) {
            return;
        }

        $facilities = collect(explode(',', $this->input('facilities')))
            ->map(fn (string $facility) => trim($facility))
            ->filter()
            ->values()
            ->all();

        $this->merge(['facilities' => $facilities]);
    }

    public function rules(): array
    {
        $busId = $this->route('bus')?->id ?? $this->route('bus');

        return [
            'bus_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('buses', 'bus_code')->ignore($busId),
            ],

            'bus_name' => [
                'required',
                'string',
                'max:255',
            ],

            'bus_type' => [
                'required',
                Rule::in([
                    'economy',
                    'executive',
                    'vip',
                    'super_vip',
                ]),
            ],

            'plate_number' => [
                'required',
                'string',
                'max:15',
                Rule::unique('buses', 'plate_number')->ignore($busId),
            ],

            'total_seats' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'facilities' => [
                'nullable',
                'array',
            ],

            'facilities.*' => [
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'maintenance',
                    'inactive',
                ]),
            ],
        ];
    }
}
