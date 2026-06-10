<?php

namespace App\Http\Requests\Backend\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:PENDING_PAYMENT,CONFIRMED,CANCELLED,COMPLETED'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => strtoupper((string) $this->input('status')),
        ]);
    }
}
