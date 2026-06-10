@extends('layouts.dashboard')

@section('title', 'Pesan Kontak - Admin')

@section('content')
<h1 class="h4 mb-3">Pesan Kontak Masuk</h1>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="all" @selected($selectedStatus === 'all')>Semua</option>
                    <option value="unread" @selected($selectedStatus === 'unread')>Belum Dibaca</option>
                    <option value="read" @selected($selectedStatus === 'read')>Sudah Dibaca</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Cari</label>
                <input type="text" name="q" class="form-control" placeholder="Nama, email, subjek, isi pesan..." value="{{ $selectedKeyword }}">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary">Reset</a>
                <span class="badge text-bg-warning ms-2">Unread: {{ $unreadCount }}</span>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th width="150">Waktu</th>
                <th>Pengirim</th>
                <th>Kontak</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th width="120">Status</th>
                <th width="140">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($messages as $message)
                <tr>
                    <td>{{ $message->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $message->full_name }}</td>
                    <td>
                        <div>{{ $message->email }}</div>
                        @if($message->phone)
                            <small class="text-muted">{{ $message->phone }}</small>
                        @endif
                    </td>
                    <td>{{ $message->subject }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($message->message, 120) }}</td>
                    <td>
                        @if($message->is_read)
                            <span class="badge text-bg-secondary">READ</span>
                        @else
                            <span class="badge text-bg-warning">UNREAD</span>
                        @endif
                    </td>
                    <td>
                        @if(!$message->is_read)
                            <form method="post" action="{{ route('admin.contact-messages.mark-read', $message) }}">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn btn-sm btn-outline-primary">Tandai Dibaca</button>
                            </form>
                        @else
                            <small class="text-muted">{{ optional($message->read_at)->format('d-m-Y H:i') ?: '-' }}</small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Belum ada pesan kontak.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $messages->links() }}</div>
@endsection
