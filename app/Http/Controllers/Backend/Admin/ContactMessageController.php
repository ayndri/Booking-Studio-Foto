<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * Daftar pesan masuk dari halaman kontak.
     */
    public function index(Request $request): View
    {
        if (!Schema::hasTable('contact_messages')) {
            abort(503, 'Tabel contact_messages belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $status = strtolower((string) $request->query('status', 'all'));
        $keyword = trim((string) $request->query('q', ''));

        if (!in_array($status, ['all', 'unread', 'read'], true)) {
            $status = 'all';
        }

        $messages = ContactMessage::query()
            ->when($status === 'unread', fn ($query) => $query->where('is_read', false))
            ->when($status === 'read', fn ($query) => $query->where('is_read', true))
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('full_name', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%')
                        ->orWhere('subject', 'like', '%' . $keyword . '%')
                        ->orWhere('message', 'like', '%' . $keyword . '%');
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('backend.admin.contact_messages.index', [
            'messages' => $messages,
            'selectedStatus' => $status,
            'selectedKeyword' => $keyword,
            'unreadCount' => ContactMessage::where('is_read', false)->count(),
        ]);
    }

    /**
     * Tandai pesan sebagai sudah dibaca.
     */
    public function markRead(ContactMessage $contactMessage): RedirectResponse
    {
        if (!Schema::hasTable('contact_messages')) {
            return back()->withErrors(['contact_messages' => 'Tabel contact_messages belum tersedia.']);
        }

        if (!$contactMessage->is_read) {
            $contactMessage->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return back()->with('success', 'Pesan berhasil ditandai sebagai sudah dibaca.');
    }
}
