<?php

namespace App\Http\Requests\Frontend;

use App\Services\Security\RecaptchaService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'guest_name' => ['required', 'string', 'max:120'],
            'guest_email' => ['required', 'email', 'max:120'],
            'guest_phone' => ['required', 'string', 'max:30'],
            'studio_id' => ['required', 'integer', 'exists:studios,id'],
            'service_package_id' => ['required', 'integer', 'exists:service_packages,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'payment_type' => ['required', 'in:DP'],
            'payment_method' => ['required', 'in:QRIS'],
            'add_on_amount' => ['nullable', 'integer', 'min:0'],
            'add_ons' => ['nullable', 'array'],
            'add_ons.*.qty' => ['nullable', 'integer', 'min:0', 'max:50'],
            'background_choice' => ['required', 'string', 'max:100'],
            'social_consent' => ['required', 'in:ALLOW,DENY'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'payment_type' => strtoupper((string) $this->input('payment_type')),
            'payment_method' => strtoupper((string) $this->input('payment_method', 'QRIS')),
            'social_consent' => strtoupper((string) $this->input('social_consent')),
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
                'booking',
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
