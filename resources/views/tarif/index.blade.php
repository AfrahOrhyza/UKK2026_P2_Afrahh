@extends('layouts.dashboard')

@section('title', 'Kelola Tarif')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Kelola Tarif</h4>
            <small class="text-muted">Manajemen tarif parkir per jenis kendaraan</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-1"></i> Tambah Tarif
        </button>
    </div>

    {{-- Alert --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">#</th>
                            <th>Jenis Kendaraan</th>
                            <th>Tarif Per Jam</th>
                            <th class="text-center pe-3" style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tarifs as $index => $tarif)
                        <tr>
                            <td class="ps-3 text-muted">{{ $tarifs->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                         style="width:38px;height:38px;min-width:38px;">
                                        <i class="bi bi-car-front text-primary"></i>
                                    </div>
                                    <div class="fw-semibold">{{ $tarif->jenis_kendaraan }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-success">
                                    Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}
                                </span>
                                <small class="text-muted">/jam</small>
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex gap-1 justify-content-center">
                                    {{-- Edit --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Edit"
                                        onclick="bukaModalEdit(
                                            {{ $tarif->id_tarif }},
                                            '{{ addslashes($tarif->jenis_kendaraan) }}',
                                            '{{ $tarif->tarif_per_jam }}'
                                        )">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    {{-- Hapus --}}
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                        onclick="bukaModalHapus({{ $tarif->id_tarif }}, '{{ addslashes($tarif->jenis_kendaraan) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-tag fs-1 d-block mb-2 opacity-25"></i>
                                Tidak ada data tarif ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($tarifs->hasPages())
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
            <small class="text-muted">
                Menampilkan {{ $tarifs->firstItem() }}–{{ $tarifs->lastItem() }}
                dari {{ $tarifs->total() }} tarif
            </small>
            {{ $tarifs->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>


{{-- ===================== MODAL TAMBAH ===================== --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('tarif.store') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="_from_modal" value="tambah">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Tarif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_kendaraan"
                            class="form-control @error('jenis_kendaraan') is-invalid @enderror"
                            value="{{ old('jenis_kendaraan') }}"
                            placeholder="Contoh: Motor, Mobil, Truk">
                        @error('jenis_kendaraan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tarif Per Jam <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="tarif_per_jam"
                                class="form-control @error('tarif_per_jam') is-invalid @enderror"
                                value="{{ old('tarif_per_jam') }}"
                                placeholder="Contoh: 2000" min="0">
                            <span class="input-group-text">/jam</span>
                        </div>
                        @error('tarif_per_jam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Tarif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_kendaraan" id="edit_jenis"
                            class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tarif Per Jam <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="tarif_per_jam" id="edit_tarif"
                                class="form-control" min="0" required>
                            <span class="input-group-text">/jam</span>
                        </div>
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
                    <p class="mb-1">Apakah Anda yakin ingin menghapus tarif:</p>
                    <p class="fw-bold fs-5" id="hapusNamaTarif"></p>
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
    function bukaModalEdit(id, jenis, tarif) {
        document.getElementById('formEdit').action = '/tarif/' + id;
        document.getElementById('edit_jenis').value = jenis;
        document.getElementById('edit_tarif').value = tarif;
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    function bukaModalHapus(id, jenis) {
        document.getElementById('formHapus').action = '/tarif/' + id;
        document.getElementById('hapusNamaTarif').textContent = jenis;
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