<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Verifikasi token Google reCAPTCHA v3 (invisible).
 *
 * Bila secret key tidak diisi, verifikasi dianggap lolos (fitur nonaktif) —
 * memudahkan pengembangan lokal tanpa harus mendaftar reCAPTCHA.
 */
class RecaptchaService
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Apakah proteksi reCAPTCHA aktif (secret key tersedia).
     */
    public function enabled(): bool
    {
        return trim((string) config('services.recaptcha.secret_key')) !== '';
    }

    /**
     * Verifikasi token dari frontend.
     *
     * @param string|null $token          Token hasil grecaptcha.execute()
     * @param string      $expectedAction Action yang diharapkan (mis. 'booking')
     * @param string|null $ip             IP pengirim (opsional)
     */
    public function verify(?string $token, string $expectedAction = '', ?string $ip = null): bool
    {
        // Fitur nonaktif → selalu lolos.
        if (!$this->enabled()) {
            return true;
        }

        $token = trim((string) $token);

        if ($token === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post(self::VERIFY_URL, [
                    'secret' => (string) config('services.recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $ip,
                ]);
        } catch (\Throwable $e) {
            // Google tidak bisa dihubungi (jaringan/timeout) → fail-open agar
            // pengguna sah tidak terblokir saat layanan Google bermasalah.
            Log::warning('reCAPTCHA tidak dapat dihubungi, verifikasi dilewati.', [
                'error' => $e->getMessage(),
            ]);

            return true;
        }

        if ($response->failed()) {
            return false;
        }

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            return false;
        }

        // v3: pastikan skor di atas ambang minimum.
        if (isset($data['score'])) {
            $minScore = (float) config('services.recaptcha.min_score', 0.5);

            if ((float) $data['score'] < $minScore) {
                return false;
            }
        }

        // Cocokkan action bila dikirim oleh Google (mencegah token dipakai ulang lintas form).
        if ($expectedAction !== '' && isset($data['action']) && $data['action'] !== $expectedAction) {
            return false;
        }

        return true;
    }
}
