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
</div>

@endsection