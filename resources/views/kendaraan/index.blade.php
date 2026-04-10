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
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kendaraan
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
                            <th>Pemilik</th>
                            <th>Status</th>
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
                            <td>{{ $kendaraan->user->name ?? '-' }}</td>
                            <td>
                                <span class="badge px-2 py-1 {{ $kendaraan->status === 'parkir' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($kendaraan->status) }}
                                </span>
                            </td>
                            <td class="text-muted small">
                                {{ \Carbon\Carbon::parse($kendaraan->created_at)->format('d M Y') }}
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex gap-1 justify-content-center">
                                    {{-- Toggle Status --}}
                                    <form action="{{ route('kendaraan.toggle-status', $kendaraan->id_kendaraan) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $kendaraan->status === 'parkir' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            title="{{ $kendaraan->status === 'parkir' ? 'Set Keluar' : 'Set Parkir' }}">
                                            <i class="bi {{ $kendaraan->status === 'parkir' ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                        </button>
                                    </form>

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
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('kendaraan.store') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="_from_modal" value="tambah">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-car-front me-2 text-primary"></i>Tambah Kendaraan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plat Nomor <span class="text-danger">*</span></label>
                        <input type="text" name="plat_nomor"
                            class="form-control @error('plat_nomor') is-invalid @enderror"
                            value="{{ old('plat_nomor') }}" placeholder="Contoh: B 1234 ABC"
                            style="text-transform:uppercase">
                        @error('plat_nomor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Warna <span class="text-danger">*</span></label>
                        <input type="text" name="warna"
                            class="form-control @error('warna') is-invalid @enderror"
                            value="{{ old('warna') }}" placeholder="Contoh: Merah">
                        @error('warna') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
                        <select name="id_tarif" class="form-select @error('id_tarif') is-invalid @enderror">
                            <option value="" disabled selected>-- Pilih Jenis --</option>
                            @foreach($tarifs as $tarif)
                                <option value="{{ $tarif->id_tarif }}"
                                    {{ old('id_tarif') == $tarif->id_tarif ? 'selected' : '' }}>
                                    {{ $tarif->jenis_kendaraan }}
                                    (Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}/jam)
                                </option>
                            @endforeach
                        </select>
                        @error('id_tarif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pemilik</label>
                        <select name="id_user" class="form-select @error('id_user') is-invalid @enderror">
                            <option value="">-- Tanpa Pemilik --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id_user }}"
                                    {{ old('id_user') == $user->id_user ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option value="parkir" {{ old('status') === 'parkir' ? 'selected' : '' }}>Parkir</option>
                            <option value="keluar" {{ old('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pemilik</label>
                        <select name="id_user" id="edit_user" class="form-select">
                            <option value="">-- Tanpa Pemilik --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id_user }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="parkir">Parkir</option>
                            <option value="keluar">Keluar</option>
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
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_tarif').value  = idTarif;
        document.getElementById('edit_user').value   = idUser;
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    function bukaModalHapus(id, plat) {
        document.getElementById('formHapus').action = '/kendaraan/' + id;
        document.getElementById('hapusNamaKendaraan').textContent = plat;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    }

    @if($errors->any() && old('_from_modal') === 'tambah')
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('modalTambah')).show();
        });
    @endif
</script>
@endpush
@endsection