<?php

namespace App\Http\Requests\Backend\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServicePackageRequest extends FormRequest
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
            'studio_id' => ['required', 'integer', 'exists:studios,id'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'price' => ['required', 'integer', 'min:1000'],
            'duration_minutes' => ['required', 'integer', 'min:15'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
