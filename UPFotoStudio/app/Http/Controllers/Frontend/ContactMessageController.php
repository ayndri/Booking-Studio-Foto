<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class ContactMessageController extends Controller
{
    /**
     * Simpan pesan dari form kontak frontend.
     */
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        if (!Schema::hasTable('contact_messages')) {
            return redirect()
                ->route('frontend.contact')
                ->withErrors(['contact' => 'Fitur form kontak belum aktif. Jalankan migrasi terlebih dahulu.']);
        }

        ContactMessage::create($request->validated());

        return redirect()
            ->route('frontend.contact')
            ->with('success', 'Pesan berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }
}
