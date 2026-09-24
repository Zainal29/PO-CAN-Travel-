<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bus_code' => [
                'required',
                'string',
                'max:20',
                'unique:buses,bus_code',
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
                'unique:buses,plate_number',
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