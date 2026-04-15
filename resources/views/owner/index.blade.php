@extends('layouts.dashboard')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="container-fluid px-4">

    {{-- Header Banner --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <small class="text-muted">Selamat datang, {{ auth()->user()->name }}</small>
    </div>

    {{-- Statistik Atas --}}
    <div class="row g-3 mb-3">

        {{-- Pendapatan Hari Ini --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted small">Pendapatan Hari Ini</p>
                        <h4 class="mb-0 fw-bold text-success">
                            Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;min-width:48px;">
                        <i class="bi bi-cash-coin text-success fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pendapatan Bulan Ini --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted small">Pendapatan Bulan Ini</p>
                        <h4 class="mb-0 fw-bold">
                            Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;min-width:48px;">
                        <i class="bi bi-bar-chart-fill text-primary fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sedang Parkir --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted small">Sedang Parkir</p>
                        <h4 class="mb-0 fw-bold">{{ $sedangParkir }} Kendaraan</h4>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;min-width:48px;">
                        <i class="bi bi-car-front text-warning fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Saldo --}}
    <div class="row g-3">

        {{-- Transaksi Hari Ini --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100"
                 style="border-left: 4px solid #22c55e !important;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted small">Transaksi Hari Ini</p>
                        <h4 class="mb-0 fw-bold text-success">
                            {{ $transaksiHariIni }} Transaksi
                        </h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;min-width:48px;">
                        <i class="bi bi-receipt text-success fs-4 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rata-rata per Transaksi --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100"
                 style="border-left: 4px solid #6366f1 !important;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted small">Rata-rata per Transaksi</p>
                        <h4 class="mb-0 fw-bold" style="color:#6366f1 !important;">
                            Rp {{ $transaksiHariIni > 0 ? number_format($pendapatanHariIni / $transaksiHariIni, 0, ',', '.') : 0 }}
                        </h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;min-width:48px;">
                        <i class="bi bi-graph-up fs-4 opacity-75" style="color:#6366f1 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection