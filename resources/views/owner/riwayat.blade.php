@extends('layouts.dashboard')

@section('title', 'Riwayat Parkir')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-clock-history me-2"></i>Riwayat Parkir
        </h5>

        <div class="d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <button id="print-struk" class="btn btn-sm text-white" style="background:#6f42c1;">
                <i class="bi bi-printer"></i>
            </button>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">

                <div class="col-md-4">
                    <label class="form-label small">Dari</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label small">Sampai</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <button class="btn w-100 text-white" style="background:#6f42c1;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <div class="col-md-1">
                    <a href="{{ route('riwayat.index') }}" class="btn btn-light w-100">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>
        </div>
    </form>

    {{-- Info Periode --}}
    @if(request('dari') && request('sampai'))
    <div class="mb-2 text-muted small">
        Periode: {{ request('dari') }} - {{ request('sampai') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold border-bottom">
            <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Transaksi
        </div>

        <div class="card-body p-0" id="area-print">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Plat</th>
                            <th>Jenis</th>
                            <th>Area</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Durasi</th>
                            <th>Biaya</th>
                            <th>Status</th>
                            <th>Metode</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($riwayat as $i => $t)
                        <tr>
                            <td class="ps-3 text-muted">{{ $i + 1 }}</td>

                            <td class="fw-semibold">
                                {{ $t->kendaraan->plat_nomor ?? '-' }}
                            </td>

                            <td>
                                {{ $t->kendaraan->tarif->jenis_kendaraan ?? '-' }}
                            </td>

                            <td>
                                {{ $t->area->nama_area ?? '-' }}
                            </td>

                            <td class="text-muted small">
                                {{ $t->waktu_masuk 
                                    ? \Carbon\Carbon::parse($t->waktu_masuk)->format('d/m/Y H:i') 
                                    : '-' 
                                }}
                            </td>

                            <td class="text-muted small">
                                {{ $t->waktu_keluar 
                                    ? \Carbon\Carbon::parse($t->waktu_keluar)->format('d/m/Y H:i') 
                                    : '-' 
                                }}
                            </td>

                            <td>
                                @if($t->waktu_keluar)
                                    {{ \Carbon\Carbon::parse($t->waktu_masuk)->diff($t->waktu_keluar)->format('%h jam %i menit') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <span class="fw-semibold text-success">
                                    Rp {{ number_format($t->biaya_total,0,',','.') }}
                                </span>
                            </td>
                            <td>
                                @if($t->status == 'aktif')
                                    <span class="badge bg-warning text-dark">Masih Parkir</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            </td>

                            <td>
                                {{ ucfirst($t->metode_pembayaran ?? '-') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2 opacity-25"></i>
                                Tidak ada data.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

{{-- PRINT SCRIPT --}}
<script>
document.getElementById('print-struk').addEventListener('click', function() {
    const printArea = document.getElementById('area-print').innerHTML;

    if (!printArea.trim()) {
        alert('Tidak ada data untuk dicetak');
        return;
    }

    const win = window.open('', '', 'width=900,height=700');

    win.document.write(`
        <html>
            <head>
                <title>Riwayat Parkir</title>
                <style>
                    body { font-family: Arial; padding: 20px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                    th { background: #6f42c1; color: white; }
                </style>
            </head>
            <body>
                <h3 style="text-align:center;">Riwayat Parkir</h3>
                ${printArea}
            </body>
        </html>
    `);

    win.document.close();
    win.print();
});
</script>

@endsection