@extends('layouts.dashboard')

@section('title', 'Kelola Kendaraan')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Kelola Kendaraan</h4>
            <small class="text-muted">Manajemen data kendaraan sistem parkir</small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="cetakKendaraan()">
                <i class="bi bi-printer me-1"></i> Cetak
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kendaraan
            </button>
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

    {{-- Filter & Search --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('kendaraan.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold mb-1">Cari</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Plat nomor atau warna..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="parkir" {{ request('status') === 'parkir' ? 'selected' : '' }}>Parkir</option>
                        <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('kendaraan.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">#</th>
                            <th>Plat Nomor</th>
                            <th>Warna</th>
                            <th>Jenis Kendaraan</th>
                            <th>Tarif / jam</th>
                            <th>Dibuat</th>
                            <th class="text-center pe-3" style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kendaraans as $index => $kendaraan)
                        <tr>
                            <td class="ps-3 text-muted">{{ $kendaraans->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $kendaraan->plat_nomor }}</div>
                                <small class="text-muted">ID: {{ $kendaraan->id_kendaraan }}</small>
                            </td>
                            <td>{{ $kendaraan->warna }}</td>
                            <td>{{ $kendaraan->tarif->jenis_kendaraan ?? '-' }}</td>
                            <td>
                            Rp {{ number_format(optional($kendaraan->tarif)->tarif_per_jam ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="text-muted small">
                                {{ \Carbon\Carbon::parse($kendaraan->created_at)->format('d M Y') }}
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex gap-1 justify-content-center">

                                    {{-- Edit --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Edit"
                                        onclick="bukaModalEdit(
                                            {{ $kendaraan->id_kendaraan }},
                                            '{{ addslashes($kendaraan->plat_nomor) }}',
                                            '{{ addslashes($kendaraan->warna) }}',
                                            '{{ $kendaraan->status }}',
                                            '{{ $kendaraan->id_tarif }}',
                                            '{{ $kendaraan->id_user }}'
                                        )">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                        onclick="bukaModalHapus({{ $kendaraan->id_kendaraan }}, '{{ addslashes($kendaraan->plat_nomor) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-car-front fs-1 d-block mb-2 opacity-25"></i>
                                Tidak ada data kendaraan ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($kendaraans->hasPages())
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
            <small class="text-muted">
                Menampilkan {{ $kendaraans->firstItem() }}–{{ $kendaraans->lastItem() }}
                dari {{ $kendaraans->total() }} kendaraan
            </small>
            {{ $kendaraans->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>


{{-- ===================== MODAL TAMBAH ===================== --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('kendaraan.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Tambah Kendaraan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plat Nomor <span class="text-danger">*</span></label>
                        <input type="text" name="plat_nomor" class="form-control"
                            placeholder="Contoh: B1234ABC" style="text-transform:uppercase" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Warna <span class="text-danger">*</span></label>
                        <input type="text" name="warna" class="form-control" placeholder="Contoh: Merah" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
                        <select name="id_tarif" class="form-select" required>
                            <option value="">-- Pilih Tarif --</option>
                            @foreach($tarifs as $tarif)
                                <option value="{{ $tarif->id_tarif }}">
                                    {{ $tarif->jenis_kendaraan }} - Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===================== MODAL EDIT ===================== --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEdit" method="POST" autocomplete="off">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Kendaraan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plat Nomor <span class="text-danger">*</span></label>
                        <input type="text" name="plat_nomor" id="edit_plat"
                            class="form-control" style="text-transform:uppercase" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Warna <span class="text-danger">*</span></label>
                        <input type="text" name="warna" id="edit_warna" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
                        <select name="id_tarif" id="edit_tarif" class="form-select" required>
                            @foreach($tarifs as $tarif)
                                <option value="{{ $tarif->id_tarif }}">
                                    {{ $tarif->jenis_kendaraan }}
                                    (Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}/jam)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ===================== MODAL HAPUS ===================== --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formHapus" method="POST">
                @csrf @method('DELETE')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:3rem;"></i>
                    </div>
                    <p class="mb-1">Apakah Anda yakin ingin menghapus kendaraan:</p>
                    <p class="fw-bold fs-5" id="hapusNamaKendaraan"></p>
                    <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-trash me-1"></i> Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function bukaModalEdit(id, plat, warna, status, idTarif, idUser) {
        document.getElementById('formEdit').action = '/kendaraan/' + id;
        document.getElementById('edit_plat').value   = plat;
        document.getElementById('edit_warna').value  = warna;
        document.getElementById('edit_tarif').value  = idTarif;
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    function bukaModalHapus(id, plat) {
        document.getElementById('formHapus').action = '/kendaraan/' + id;
        document.getElementById('hapusNamaKendaraan').textContent = plat;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    }

    function cetakKendaraan() {
        var table = document.querySelector('table');
        var rows  = table.querySelectorAll('tbody tr');
        var rowsHtml = '';

        rows.forEach(function(row) {
            var cols = row.querySelectorAll('td');
            if (cols.length < 7) return;

            var no      = cols[0] ? cols[0].innerText.trim() : '';
            var plat    = cols[1] ? cols[1].innerText.trim() : '';
            var warna   = cols[2] ? cols[2].innerText.trim() : '';
            var jenis   = cols[3] ? cols[3].innerText.trim() : '';
            var pemilik = cols[4] ? cols[4].innerText.trim() : '';
            var status  = cols[5] ? cols[5].innerText.trim() : '';
            var dibuat  = cols[6] ? cols[6].innerText.trim() : '';

            rowsHtml += '<tr>'
                + '<td>' + no      + '</td>'
                + '<td>' + plat    + '</td>'
                + '<td>' + warna   + '</td>'
                + '<td>' + jenis   + '</td>'
                + '<td>' + pemilik + '</td>'
                + '<td>' + status  + '</td>'
                + '<td>' + dibuat  + '</td>'
                + '</tr>';
        });

        var tanggal = new Date().toLocaleDateString('id-ID', {
            day: '2-digit', month: 'long', year: 'numeric'
        });

        var html = '<!DOCTYPE html>'
            + '<html><head>'
            + '<meta charset="UTF-8">'
            + '<title>Laporan Kendaraan</title>'
            + '<style>'
            + 'body { font-family: Arial, sans-serif; font-size: 13px; padding: 24px; }'
            + 'h2 { text-align: center; margin-bottom: 4px; }'
            + '.sub { text-align: center; color: #666; font-size: 12px; margin-bottom: 20px; }'
            + 'table { width: 100%; border-collapse: collapse; }'
            + 'th { background: #f0f0f0; font-weight: bold; }'
            + 'th, td { border: 1px solid #ccc; padding: 7px 10px; text-align: left; vertical-align: top; }'
            + '.btn-print { margin-top: 16px; padding: 8px 20px; font-size: 13px; cursor: pointer; }'
            + '@media print { .btn-print { display: none; } }'
            + '</style>'
            + '</head><body>'
            + '<h2>Laporan Data Kendaraan</h2>'
            + '<p class="sub">Dicetak pada: ' + tanggal + '</p>'
            + '<table>'
            + '<thead><tr>'
            + '<th>#</th>'
            + '<th>Plat Nomor</th>'
            + '<th>Warna</th>'
            + '<th>Jenis Kendaraan</th>'
            + '<th>Pemilik</th>'
            + '<th>Status</th>'
            + '<th>Dibuat</th>'
            + '</tr></thead>'
            + '<tbody>' + rowsHtml + '</tbody>'
            + '</table>'
            + '<button class="btn-print" onclick="window.print()">Print Sekarang</button>'
            + '</body></html>';

        var win = window.open('', '_blank');
        if (!win) {
            alert('Popup diblokir oleh browser!\nSilakan izinkan popup untuk halaman ini, lalu coba lagi.');
            return;
        }
        win.document.write(html);
        win.document.close();
    }

    @if($errors->any() && old('_from_modal') === 'tambah')
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('modalTambah')).show();
        });
    @endif
</script>
@endpush
@endsection