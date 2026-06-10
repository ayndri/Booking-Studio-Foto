<?php

namespace App\Http\Requests\Backend\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteContentRequest extends FormRequest
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
            'section' => ['nullable', 'in:all,home,about,gallery,pricing,contact,terms,footer,services,promo'],
            'home_menu' => ['nullable', 'in:carousel,header,gallery,services,faq,footer'],
            'content_type' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:150'],
            'text_content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
