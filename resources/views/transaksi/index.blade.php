@extends('layouts.dashboard')

@section('title', 'Transaksi Parkir')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Transaksi Parkir</h4>
            <small class="text-muted">Manajemen transaksi masuk & keluar kendaraan</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-1"></i> Kendaraan Masuk
        </button>
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
            <form method="GET" action="{{ route('transaksi.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold mb-1">Cari Plat / Warna</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari plat nomor atau warna..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="aktif"   {{ request('status') === 'aktif'   ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Data Transaksi Parkir</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">No</th>
                            <th>Plat Kendaraan</th>
                            <th>Warna</th>
                            <th>Area</th>
                            <th>Waktu Parkir</th>
                            <th>Durasi</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th class="text-center pe-3" style="width:140px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $index => $t)
                        <tr>
                            <td class="ps-3 text-muted">{{ $transaksis->firstItem() + $index }}</td>

                            <td class="fw-semibold">{{ $t->kendaraan->plat_nomor ?? '-' }}</td>
                            <td>{{ $t->kendaraan->warna ?? '-' }}</td>
                            <td>{{ $t->area->nama_area ?? '-' }}</td>

                            {{-- WAKTU --}}
                            <td class="small">
                                {{ $t->waktu_masuk ? \Carbon\Carbon::parse($t->waktu_masuk)->format('d M Y H:i') : '-' }}
                                @if($t->waktu_keluar)
                                    <br>
                                    <small class="text-muted">
                                        Keluar: {{ \Carbon\Carbon::parse($t->waktu_keluar)->format('d M Y H:i') }}
                                    </small>
                                @endif
                            </td>

                            {{-- DURASI --}}
                            <td>
                                @if($t->waktu_masuk)
                                    @php
                                        $mulai = \Carbon\Carbon::parse($t->waktu_masuk);
                                        $akhir = $t->waktu_keluar ? \Carbon\Carbon::parse($t->waktu_keluar) : now();

                                        $durasiMenit = $mulai->diffInMinutes($akhir);
                                        $jam = floor($durasiMenit / 60);
                                        $menit = $durasiMenit % 60;
                                    @endphp

                                    {{ $jam }} jam {{ $menit }} menit
                                @else
                                    -
                                @endif
                            </td>

                            {{-- TOTAL --}}
                            <td>
                                @if($t->status === 'selesai')
                                    <span class="fw-semibold text-success">
                                        Rp {{ number_format($t->biaya_total, 0, ',', '.') }}
                                    </span>
                                @else
                                    @php
                                        $menit = \Carbon\Carbon::parse($t->waktu_masuk)->diffInMinutes(now());
                                        $jam = ceil($menit / 60);
                                        if ($jam < 1) $jam = 1;

                                        $tarif = $t->tarif->tarif_per_jam ?? 0;
                                        $estimasi = $jam * $tarif;
                                    @endphp

                                    <span class="text-warning small">
                                        Rp {{ number_format($estimasi, 0, ',', '.') }}
                                    </span>
                                    <br><small class="text-muted">(estimasi)</small>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($t->status === 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Keluar</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center pe-3">
                                <div class="d-flex gap-1 justify-content-center">

                                    @if($t->status === 'aktif')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-warning"
                                            onclick="bukaModalSelesai(
                                                {{ $t->id_transaksi }},
                                                '{{ $t->kendaraan->plat_nomor ?? '-' }}',
                                                '{{ \Carbon\Carbon::parse($t->waktu_masuk)->format('d M Y H:i') }}'
                                            )">
                                            <i class="bi bi-box-arrow-right"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('transaksi.struk', $t->id_transaksi) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    @endif

                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="bukaModalHapus({{ $t->id_transaksi }}, '{{ $t->kendaraan->plat_nomor ?? '-' }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                Tidak ada data transaksi.
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                </table>
            </div>
        </div>

        @if($transaksis->hasPages())
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
            <small class="text-muted">
                Menampilkan {{ $transaksis->firstItem() }}–{{ $transaksis->lastItem() }}
                dari {{ $transaksis->total() }} transaksi
            </small>
            {{ $transaksis->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>


{{-- ===================== MODAL TAMBAH ===================== --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>Kendaraan Masuk
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih Kendaraan</label>
                    <select name="id_kendaraan" class="form-control">
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach($kendaraan as $k)
                            <option value="{{ $k->id_kendaraan }}">
                                {{ $k->plat_nomor }} - {{ $k->warna }}
                            </option>
                        @endforeach
                    </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Area Parkir <span class="text-danger">*</span></label>
                        <select name="id_area" class="form-select" required>
                            <option value="">-- Pilih Area --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id_area }}">
                                    {{ $area->nama_area }} (Sisa: {{ $area->kapasitas - $area->terisi }} slot)
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
            </form>
        </div>
    </div>
</div>


{{-- ===================== MODAL SELESAI / KELUAR ===================== --}}
<div class="modal fade" id="modalSelesai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formSelesai" method="POST">
                @csrf @method('PATCH')

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-box-arrow-right me-2 text-warning"></i>Proses Keluar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center py-4">
                    <div class="text-warning mb-3">
                        <i class="bi bi-car-front-fill" style="font-size:3rem;"></i>
                    </div>

                    <p class="mb-1">Proses keluar untuk kendaraan:</p>
                    <p class="fw-bold fs-5" id="selesaiPlat"></p>

                    <p class="text-muted small mb-2">
                        Waktu masuk: <span id="selesaiWaktu"></span>
                    </p>

                    <hr>

                    {{-- METODE PEMBAYARAN --}}
                    <div class="text-start">
                        <label class="fw-semibold mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <p class="text-muted small mt-2">
                        Struk akan langsung dicetak setelah pembayaran.
                    </p>
                </div>

                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-printer me-1"></i> Bayar & Cetak
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
                    <p class="mb-1">Apakah Anda yakin ingin menghapus transaksi kendaraan:</p>
                    <p class="fw-bold fs-5" id="hapusPlat"></p>
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

<!-- MODAL STRUK -->
<div class="modal fade" id="modalStruk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3" id="strukContent">
            <div class="text-center">
                <h6>STRUK PARKIR</h6>
                <hr>

                <div id="isiStruk"></div>

                <button class="btn btn-sm btn-primary mt-3" onclick="printStruk()">Cetak</button>
            </div>
        </div>
    </div>
</div>

<script>
    function bukaModalSelesai(id, plat, waktu) {
        document.getElementById('formSelesai').action = '/transaksi/' + id + '/selesai';
        document.getElementById('selesaiPlat').textContent  = plat;
        document.getElementById('selesaiWaktu').textContent = waktu;
        var modalEl = document.getElementById('modalSelesai');
        var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
    }

    function bukaModalHapus(id, plat) {
        document.getElementById('formHapus').action = '/transaksi/' + id;
        document.getElementById('hapusPlat').textContent = plat;
        var modalEl = document.getElementById('modalHapus');
        var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
    }
    
    function bukaModalSelesai(id, plat, waktu) {
    document.getElementById('formSelesai').action = '/transaksi/' + id + '/selesai';
    document.getElementById('selesaiPlat').textContent  = plat;
    document.getElementById('selesaiWaktu').textContent = waktu;
    var modalEl = document.getElementById('modalSelesai');
    var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    modal.show();
}
</script>
@push('scripts')
<script>
function bukaModalSelesai(id, plat, waktu) {
    document.getElementById('formSelesai').action = '/transaksi/' + id + '/selesai';
    document.getElementById('selesaiPlat').textContent  = plat;
    document.getElementById('selesaiWaktu').textContent = waktu;

    let modal = new bootstrap.Modal(document.getElementById('modalSelesai'));
    modal.show();
}

function bukaModalHapus(id, plat) {
    document.getElementById('formHapus').action = '/transaksi/' + id;
    document.getElementById('hapusPlat').textContent = plat;

    let modal = new bootstrap.Modal(document.getElementById('modalHapus'));
    modal.show();
}
</script>
@endpush
@endsection