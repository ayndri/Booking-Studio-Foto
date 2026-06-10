@extends('layouts.dashboard')
@section('title', 'Pesan Kontak - Admin')
@section('content')

<div class="page-header">
    <h1 class="page-title">✉️ Pesan Kontak Masuk</h1>
    @if($unreadCount > 0)
        <span class="dbadge dbadge-unread">{{ $unreadCount }} belum dibaca</span>
    @endif
</div>

<div class="filter-bar">
    <form method="get" style="display:contents">
        <div class="filter-field">
            <label>Status</label>
            <select name="status" class="d-select" style="width:auto;min-width:150px">
                <option value="all"    @selected($selectedStatus==='all')>Semua</option>
                <option value="unread" @selected($selectedStatus==='unread')>Belum Dibaca</option>
                <option value="read"   @selected($selectedStatus==='read')>Sudah Dibaca</option>
            </select>
        </div>
        <div class="filter-field" style="flex:1;min-width:220px">
            <label>Cari</label>
            <input type="text" name="q" class="d-input" placeholder="Nama, email, subjek, isi pesan..." value="{{ $selectedKeyword }}">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-g">Cari</button>
            <a href="{{ route('admin.contact-messages.index') }}" class="btn-back">Reset</a>
        </div>
    </form>
</div>

<div class="d-card">
    <div class="table-responsive">
        <table class="d-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengirim</th>
                    <th>Kontak</th>
                    <th>Subjek</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($messages as $msg)
                <tr style="{{ !$msg->is_read ? 'background:#fffbf0' : '' }}">
                    <td style="white-space:nowrap;color:#888;font-size:.8rem">{{ $msg->created_at->format('d M Y') }}<br>{{ $msg->created_at->format('H:i') }}</td>
                    <td style="font-weight:{{ !$msg->is_read ? '700' : '500' }}">{{ $msg->full_name }}</td>
                    <td style="font-size:.82rem">
                        <div>{{ $msg->email }}</div>
                        @if($msg->phone)<div style="color:#888">{{ $msg->phone }}</div>@endif
                    </td>
                    <td style="font-weight:600;font-size:.88rem">{{ $msg->subject }}</td>
                    <td style="color:#666;font-size:.84rem">{{ \Illuminate\Support\Str::limit($msg->message, 100) }}</td>
                    <td>
                        @if($msg->is_read)
                            <span class="dbadge dbadge-read">Read</span>
                        @else
                            <span class="dbadge dbadge-unread">Unread</span>
                        @endif
                    </td>
                    <td>
                        @if(!$msg->is_read)
                            <form method="post" action="{{ route('admin.contact-messages.mark-read', $msg) }}">
                                @csrf @method('patch')
                                <button type="submit" class="btn-edit" style="font-size:.76rem">✓ Tandai Dibaca</button>
                            </form>
                        @else
                            <span style="font-size:.74rem;color:#bbb">{{ optional($msg->read_at)->format('d M, H:i') ?? '–' }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#bbb;padding:32px">Belum ada pesan kontak.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $messages->links() }}</div>
@endsection
