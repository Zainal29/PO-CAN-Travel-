<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cancellation_note' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'cancellation_note.required' =>
                'Alasan pembatalan wajib diisi.',

            'cancellation_note.string' =>
                'Alasan pembatalan harus berupa teks.',

            'cancellation_note.min' =>
                'Alasan pembatalan minimal 5 karakter.',

            'cancellation_note.max' =>
                'Alasan pembatalan maksimal 1000 karakter.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('cancellation_note')) {
            $this->merge([
                'cancellation_note' => trim(
                    (string) $this->cancellation_note
                ),
            ]);
        }
    }
}
