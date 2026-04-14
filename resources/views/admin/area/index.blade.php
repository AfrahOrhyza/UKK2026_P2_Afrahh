@extends('layouts.dashboard')

@section('title', 'Kelola Area Parkir')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Kelola Area Parkir</h4>
            <small class="text-muted">Manajemen data area parkir</small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="cetakArea()">
                <i class="bi bi-printer me-1"></i> Cetak
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-1"></i> Tambah Area
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
            <form method="GET" action="{{ route('area.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold mb-1">Cari</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Nama area..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('area.index') }}" class="btn btn-outline-secondary">
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
                            <th>Nama Area</th>
                            <th class="text-center">Kapasitas</th>
                            <th class="text-center">Terisi</th>
                            <th class="text-center">Sisa</th>
                            <th class="text-center pe-3" style="width:130px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($areas as $index => $area)
                        @php
                            $sisa = $area->kapasitas - $area->terisi;
                        @endphp
                        <tr>
                            <td class="ps-3 text-muted">{{ $areas->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $area->nama_area }}</div>
                                <small class="text-muted">ID: {{ $area->id_area }}</small>
                            </td>
                            <td class="text-center">{{ $area->kapasitas }}</td>
                            <td class="text-center">{{ $area->terisi }}</td>
                            <td class="text-center">
                                <span class="badge px-2 py-1 {{ $sisa > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $sisa }}
                                </span>
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex gap-1 justify-content-center">
                                    {{-- Edit --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Edit"
                                        onclick="bukaModalEdit(
                                            {{ $area->id_area }},
                                            '{{ addslashes($area->nama_area) }}',
                                            {{ $area->kapasitas }},
                                            {{ $area->terisi }}
                                        )">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                        onclick="bukaModalHapus({{ $area->id_area }}, '{{ addslashes($area->nama_area) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-map fs-1 d-block mb-2 opacity-25"></i>
                                Tidak ada data area parkir ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($areas->hasPages())
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
            <small class="text-muted">
                Menampilkan {{ $areas->firstItem() }}–{{ $areas->lastItem() }}
                dari {{ $areas->total() }} area
            </small>
            {{ $areas->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>


{{-- ===================== MODAL TAMBAH ===================== --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('area.store') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="_from_modal" value="tambah">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-map me-2 text-primary"></i>Tambah Area Parkir
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Area <span class="text-danger">*</span></label>
                        <input type="text" name="nama_area"
                            class="form-control @error('nama_area') is-invalid @enderror"
                            value="{{ old('nama_area') }}"
                            placeholder="Contoh: Lantai 1, Area A, Basement">
                        @error('nama_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kapasitas <span class="text-danger">*</span></label>
                        <input type="number" name="kapasitas"
                            class="form-control @error('kapasitas') is-invalid @enderror"
                            value="{{ old('kapasitas') }}"
                            min="1" placeholder="Jumlah slot tersedia">
                        @error('kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Jumlah slot terisi akan dimulai dari 0.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
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
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Area Parkir
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Area <span class="text-danger">*</span></label>
                        <input type="text" name="nama_area" id="edit_nama"
                            class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kapasitas <span class="text-danger">*</span></label>
                        <input type="number" name="kapasitas" id="edit_kapasitas"
                            class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Terisi <span class="text-danger">*</span></label>
                        <input type="number" name="terisi" id="edit_terisi"
                            class="form-control" min="0" required>
                        <div class="form-text">Jumlah slot yang saat ini sedang terisi.</div>
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
                    <p class="mb-1">Apakah Anda yakin ingin menghapus area:</p>
                    <p class="fw-bold fs-5" id="hapusNamaArea"></p>
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
    function bukaModalEdit(id, nama, kapasitas, terisi) {
        document.getElementById('formEdit').action = '/area/' + id;
        document.getElementById('edit_nama').value      = nama;
        document.getElementById('edit_kapasitas').value = kapasitas;
        document.getElementById('edit_terisi').value    = terisi;
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    function bukaModalHapus(id, nama) {
        document.getElementById('formHapus').action = '/area/' + id;
        document.getElementById('hapusNamaArea').textContent = nama;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    }

    function cetakArea() {
        var table = document.querySelector('table');
        var rows  = table.querySelectorAll('tbody tr');
        var rowsHtml = '';

        rows.forEach(function(row) {
            var cols = row.querySelectorAll('td');
            if (cols.length < 5) return;

            var no        = cols[0] ? cols[0].innerText.trim() : '';
            var nama      = cols[1] ? cols[1].innerText.trim() : '';
            var kapasitas = cols[2] ? cols[2].innerText.trim() : '';
            var terisi    = cols[3] ? cols[3].innerText.trim() : '';
            var sisa      = cols[4] ? cols[4].innerText.trim() : '';

            rowsHtml += '<tr>'
                + '<td>' + no        + '</td>'
                + '<td>' + nama      + '</td>'
                + '<td style="text-align:center">' + kapasitas + '</td>'
                + '<td style="text-align:center">' + terisi    + '</td>'
                + '<td style="text-align:center">' + sisa      + '</td>'
                + '</tr>';
        });

        var tanggal = new Date().toLocaleDateString('id-ID', {
            day: '2-digit', month: 'long', year: 'numeric'
        });

        var html = '<!DOCTYPE html>'
            + '<html><head>'
            + '<meta charset="UTF-8">'
            + '<title>Laporan Area Parkir</title>'
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
            + '<h2>Laporan Data Area Parkir</h2>'
            + '<p class="sub">Dicetak pada: ' + tanggal + '</p>'
            + '<table>'
            + '<thead><tr>'
            + '<th>#</th>'
            + '<th>Nama Area</th>'
            + '<th style="text-align:center">Kapasitas</th>'
            + '<th style="text-align:center">Terisi</th>'
            + '<th style="text-align:center">Sisa</th>'
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