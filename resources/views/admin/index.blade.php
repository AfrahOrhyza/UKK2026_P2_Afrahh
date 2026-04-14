@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <small class="text-muted">Selamat datang, {{ auth()->user()->name }}</small>
    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">

        {{-- Jumlah User --}}
        <div class="col-md-6 col-xxl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 text-muted">Jumlah User</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalUser }}</h3>
                        <small class="text-muted">Total semua user</small>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;min-width:52px;">
                        <i class="bi bi-people text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jumlah Kendaraan --}}
        <div class="col-md-6 col-xxl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 text-muted">Jumlah Kendaraan</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalKendaraan }}</h3>
                        <small class="text-muted">Kendaraan terdaftar</small>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;min-width:52px;">
                        <i class="bi bi-car-front text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Masuk Hari Ini --}}
        <div class="col-md-6 col-xxl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 text-muted">Masuk Hari Ini</h6>
                        <h3 class="mb-0 fw-bold">{{ $masukHariIni }}</h3>
                        <small class="text-muted">Dari waktu_masuk</small>
                    </div>
                    <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;min-width:52px;">
                        <i class="bi bi-box-arrow-in-right text-info fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Keluar Hari Ini --}}
        <div class="col-md-6 col-xxl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 text-muted">Keluar Hari Ini</h6>
                        <h3 class="mb-0 fw-bold">{{ $keluarHariIni }}</h3>
                        <small class="text-muted">Dari waktu_keluar</small>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;min-width:52px;">
                        <i class="bi bi-box-arrow-in-left text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Area Parkir --}}
        <div class="col-md-6 col-xxl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 text-muted">Area Parkir</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalArea }}</h3>
                        <small class="text-muted">Total area tersedia</small>
                    </div>
                    <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;min-width:52px;">
                        <i class="bi bi-geo-alt text-secondary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transaksi Aktif --}}
        <div class="col-md-6 col-xxl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 text-muted">Transaksi Aktif</h6>
                        <h3 class="mb-0 fw-bold">{{ $transaksiAktif }}</h3>
                        <small class="text-muted">Sedang parkir</small>
                    </div>
                    <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;min-width:52px;">
                        <i class="bi bi-receipt text-danger fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Transaksi Terbaru --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold border-bottom">
            <i class="bi bi-clock-history me-2 text-primary"></i>Transaksi Terbaru
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Plat</th>
                            <th>Jenis</th>
                            <th>Area</th>
                            <th>Waktu Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $i => $t)
                        <tr>
                            <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $t->kendaraan->plat_nomor ?? '-' }}</td>
                            <td>{{ $t->kendaraan->tarif->jenis_kendaraan ?? '-' }}</td>
                            <td>{{ $t->area->nama_area ?? '-' }}</td>
                            <td class="text-muted small">
                                {{ $t->waktu_masuk ? \Carbon\Carbon::parse($t->waktu_masuk)->format('d M Y H:i') : '-' }}
                            </td>
                            <td>
                                <span class="badge {{ $t->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-25"></i>
                                Belum ada transaksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection