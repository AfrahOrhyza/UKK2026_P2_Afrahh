@extends('layouts.dashboard')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Log Aktivitas</h4>
            <small class="text-muted">Riwayat aktivitas pengguna sistem</small>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('log.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">Cari Aktivitas</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Kata kunci aktivitas..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Pengguna</label>
                    <select name="id_user" class="form-select">
                        <option value="">-- Semua Pengguna --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id_user }}"
                                {{ request('id_user') == $user->id_user ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                        value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('log.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Info total --}}
    @if($logs->total() > 0)
    <div class="mb-3">
        <small class="text-muted">
            Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} log
        </small>
    </div>
    @endif

    {{-- Log Feed --}}
    @forelse($logs as $log)
    @php
        $colorMap = ['admin' => 'danger', 'petugas' => 'warning', 'user' => 'primary'];
        $color    = $colorMap[$log->user->role ?? ''] ?? 'secondary';
    @endphp
    <div class="card shadow-sm mb-3 border-0 border-start border-4 border-{{ $color }}">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-start justify-content-between gap-3">

                {{-- Avatar + Detail --}}
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-{{ $color }} bg-opacity-10 text-{{ $color }} d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                         style="width:42px;height:42px;font-size:15px;">
                        {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : 'S' }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <span class="fw-semibold">{{ $log->user->name ?? 'Sistem' }}</span>
                            @if($log->user)
                            <span class="badge px-2 py-1 bg-{{ $color }} {{ $color === 'warning' ? 'text-dark' : '' }}"
                                  style="font-size:10px;">
                                {{ ucfirst($log->user->role) }}
                            </span>
                            @endif
                        </div>
                        <p class="mb-1 text-dark" style="font-size:14px;">{{ $log->aktivitas }}</p>
                        <div class="d-flex align-items-center gap-1 text-muted" style="font-size:12px;">
                            <i class="bi bi-clock"></i>
                            <span>{{ \Carbon\Carbon::parse($log->waktu_aktivitas)->format('d M Y, H:i:s') }}</span>
                            <span class="mx-1">·</span>
                            <span>{{ \Carbon\Carbon::parse($log->waktu_aktivitas)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Hapus --}}
                <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" title="Hapus"
                    onclick="bukaModalHapus({{ $log->id_log }}, '{{ addslashes(\Illuminate\Support\Str::limit($log->aktivitas, 50)) }}')">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-journal-x fs-1 d-block mb-2 opacity-25"></i>
            Tidak ada log aktivitas ditemukan.
        </div>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}</small>
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>


@push('scripts')
@endpush
@endsection