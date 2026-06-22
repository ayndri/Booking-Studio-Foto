<?php

namespace App\Http\Requests\Frontend;

use App\Services\Security\RecaptchaService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'full_name' => trim((string) $this->input('full_name')),
            'email' => trim((string) $this->input('email')),
            'phone' => trim((string) $this->input('phone')),
            'subject' => trim((string) $this->input('subject')),
            'message' => trim((string) $this->input('message')),
        ]);
    }

    /**
     * Verifikasi token reCAPTCHA setelah validasi field utama.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $passed = app(RecaptchaService::class)->verify(
                $this->input('recaptcha_token'),
                'contact',
                $this->ip()
            );

            if (!$passed) {
                $validator->errors()->add(
                    'recaptcha_token',
                    'Verifikasi anti-bot gagal. Silakan muat ulang halaman lalu coba lagi.'
                );
            }
        });
    }
}
