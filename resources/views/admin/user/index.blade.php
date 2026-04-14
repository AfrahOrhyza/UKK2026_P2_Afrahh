@extends('layouts.dashboard')

@section('title', 'Kelola User')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Kelola User</h4>
            <small class="text-muted">Manajemen data pengguna sistem parkir</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-1"></i> Tambah User
        </button>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter & Search --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('user.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold mb-1">Cari</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Nama atau email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Role</label>
                    <select name="role" class="form-select">
                        <option value="">-- Semua Role --</option>
                        <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ request('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="owner"   {{ request('role') === 'owner'   ? 'selected' : '' }}>Owner</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= DATA ADMIN ================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">Data Admin</h6>
                <button onclick="printTable('adminTable', 'Data Admin')" class="btn btn-success btn-sm">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
            <div id="adminTable" class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Nama</th><th>Email</th><th>Status</th><th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($users->where('role','admin') as $user)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            {{-- FIX: tidak hardcode bg-success, ikut nilai status --}}
                            <td>
                                <span class="badge bg-{{ $user->status === 'aktif' ? 'success' : 'danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Edit"
                                    onclick="bukaModalEdit({{ $user->id_user }},'{{ addslashes($user->name) }}','{{ $user->email }}','{{ $user->role }}','{{ $user->status }}','')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                    onclick="bukaModalHapus({{ $user->id_user }},'{{ addslashes($user->name) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data admin</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= DATA PETUGAS ================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">Data Petugas</h6>
                <button onclick="printTable('petugasTable', 'Data Petugas')" class="btn btn-success btn-sm">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
            <div id="petugasTable" class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Nama</th><th>Email</th><th>Shift</th>
                            <th>Status</th><th>Dibuat</th><th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no  = 1;
                            $jam = now()->setTimezone('Asia/Jakarta')->hour;
                        @endphp
                        @forelse($users->where('role','petugas') as $user)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $isOn = match($user->shift) {
                                        'pagi'  => $jam >= 6  && $jam < 14,
                                        'siang' => $jam >= 14 && $jam < 22,
                                        'malam' => $jam >= 22 || $jam < 6,
                                        default => false,
                                    };
                                    $shiftColor = match($user->shift) {
                                        'pagi'  => 'warning',
                                        'siang' => 'info',
                                        'malam' => 'dark',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $shiftColor }}">
                                    {{ $user->shift ? ucfirst($user->shift) : '-' }}
                                </span>
                            </td>
                              <td>
                                <span class="badge bg-{{ $isOn ? 'success' : 'danger' }}">
                                    {{ $isOn ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Edit"
                                    onclick="bukaModalEdit({{ $user->id_user }},'{{ addslashes($user->name) }}','{{ $user->email }}','{{ $user->role }}','{{ $user->status }}','{{ $user->shift }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                    onclick="bukaModalHapus({{ $user->id_user }},'{{ addslashes($user->name) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data petugas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= DATA OWNER ================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">Data Owner</h6>
                <button onclick="printTable('ownerTable', 'Data Owner')" class="btn btn-success btn-sm">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
            <div id="ownerTable" class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Nama</th><th>Email</th><th>Status</th><th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($users->where('role','owner') as $user)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            {{-- FIX: tidak hardcode bg-success, ikut nilai status --}}
                            <td>
                                <span class="badge bg-{{ $user->status === 'aktif' ? 'success' : 'danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Edit"
                                    onclick="bukaModalEdit({{ $user->id_user }},'{{ addslashes($user->name) }}','{{ $user->email }}','{{ $user->role }}','{{ $user->status }}','')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                    onclick="bukaModalHapus({{ $user->id_user }},'{{ addslashes($user->name) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data owner</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ===================== MODAL TAMBAH USER ===================== --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="labelTambah" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('user.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="_from_modal" value="tambah">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="labelTambah">
                            <i class="bi bi-person-plus me-2 text-primary"></i>Tambah User
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="contoh@email.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="tambah_password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePwd('tambah_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="tambah_password_confirm"
                                    class="form-control" placeholder="Ulangi password">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePwd('tambah_password_confirm', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                <select name="role" id="roleTambah" class="form-select @error('role') is-invalid @enderror">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                                    <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    <option value="owner"   {{ old('role') === 'owner'   ? 'selected' : '' }}>Owner</option>
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="aktif"    {{ old('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            {{-- Shift: muncul hanya saat role = petugas --}}
                            <div class="col-6 d-none" id="fieldShift">
                                <label class="form-label fw-semibold">Shift <span class="text-danger">*</span></label>
                                <select name="shift" class="form-select @error('shift') is-invalid @enderror">
                                    <option value="">-- Pilih Shift --</option>
                                    <option value="pagi"  {{ old('shift') === 'pagi'  ? 'selected' : '' }}>Pagi</option>
                                    <option value="siang" {{ old('shift') === 'siang' ? 'selected' : '' }}>Siang</option>
                                    <option value="malam" {{ old('shift') === 'malam' ? 'selected' : '' }}>Malam</option>
                                </select>
                                @error('shift') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
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


    {{-- ===================== MODAL EDIT USER ===================== --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="labelEdit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formEdit" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="labelEdit">
                            <i class="bi bi-pencil-square me-2 text-warning"></i>Edit User
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control"
                                placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-control"
                                placeholder="contoh@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Password Baru
                                <span class="text-muted fw-normal small">(kosongkan jika tidak diubah)</span>
                            </label>
                            <div class="input-group">
                                <input type="password" name="password" id="edit_password"
                                    class="form-control" placeholder="Masukkan password baru">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePwd('edit_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="edit_password_confirm"
                                    class="form-control" placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePwd('edit_password_confirm', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                <select name="role" id="edit_role" class="form-select" required>
                                    <option value="admin">Admin</option>
                                    <option value="petugas">Petugas</option>
                                    <option value="owner">Owner</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                            {{-- Shift edit: muncul hanya saat role = petugas --}}
                            <div class="col-6 d-none" id="fieldShiftEdit">
                                <label class="form-label fw-semibold">Shift <span class="text-danger">*</span></label>
                                <select name="shift" id="edit_shift" class="form-select">
                                    <option value="">-- Pilih Shift --</option>
                                    <option value="pagi">Pagi</option>
                                    <option value="siang">Siang</option>
                                    <option value="malam">Malam</option>
                                </select>
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


    {{-- ===================== MODAL HAPUS USER ===================== --}}
    <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="labelHapus" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="labelHapus">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="text-danger mb-3">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size:3rem;"></i>
                        </div>
                        <p class="mb-1">Apakah Anda yakin ingin menghapus user:</p>
                        <p class="fw-bold fs-5" id="hapusNamaUser"></p>
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

</div>

@push('scripts')
<script>
    let _pendingShift  = '';
    let _pendingRole   = '';
    let _pendingStatus = '';

    // ── Modal Edit ──────────────────────────────────────────────
    function bukaModalEdit(id, nama, email, role, status, shift) {
        document.getElementById('formEdit').action             = '/user/' + id;
        document.getElementById('edit_name').value             = nama;
        document.getElementById('edit_email').value            = email;
        document.getElementById('edit_password').value         = '';
        document.getElementById('edit_password_confirm').value = '';

        // Simpan ke variabel luar agar tidak ter-overwrite closure
        _pendingRole   = role   || '';
        _pendingStatus = status || '';
        _pendingShift  = shift  || '';

        const modalEl       = document.getElementById('modalEdit');
        const modalInstance = new bootstrap.Modal(modalEl);

        // Set semua nilai SETELAH modal benar-benar tampil
        // agar tidak di-reset animasi Bootstrap
        modalEl.addEventListener('shown.bs.modal', function handler() {
            const roleSelect   = document.getElementById('edit_role');
            const statusSelect = document.getElementById('edit_status');
            const shiftField   = document.getElementById('fieldShiftEdit');
            const shiftSelect  = document.getElementById('edit_shift');

            roleSelect.value   = _pendingRole;
            statusSelect.value = _pendingStatus;

            if (_pendingRole === 'petugas') {
                shiftField.classList.remove('d-none');
                shiftSelect.value = _pendingShift;
            } else {
                shiftField.classList.add('d-none');
                shiftSelect.value = '';
            }

            // Saat role diubah di dalam modal
            roleSelect.onchange = function () {
                _pendingShift = '';
                if (this.value === 'petugas') {
                    shiftField.classList.remove('d-none');
                } else {
                    shiftField.classList.add('d-none');
                    shiftSelect.value = '';
                }
            };

            modalEl.removeEventListener('shown.bs.modal', handler);
        });

        modalInstance.show();
    }

    // ── Modal Hapus ─────────────────────────────────────────────
    function bukaModalHapus(id, nama) {
        document.getElementById('formHapus').action          = '/user/' + id;
        document.getElementById('hapusNamaUser').textContent = nama;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    }

    // ── Toggle Password Visibility ──────────────────────────────
    function togglePwd(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // ── Print Table ─────────────────────────────────────────────
    function printTable(divId, title) {
        const content = document.getElementById(divId)?.innerHTML;
        if (!content) return;

        const printWindow = window.open('', '', 'width=900,height=600');
        printWindow.document.write(`
            <html>
            <head>
                <title>${title}</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    h3 { text-align: center; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; }
                    table, th, td { border: 1px solid #000; }
                    th, td { padding: 8px; text-align: left; }
                </style>
            </head>
            <body>
                <h3>${title}</h3>
                ${content}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }

    // ── Re-open modal tambah jika ada validation error ──────────
    @if($errors->any() && old('_from_modal') === 'tambah')
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('modalTambah')).show();
        });
    @endif

    // ── Toggle field Shift di modal tambah ──────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('roleTambah');
        const shiftField = document.getElementById('fieldShift');

        function toggleShift() {
            if (!roleSelect || !shiftField) return;
            if (roleSelect.value === 'petugas') {
                shiftField.classList.remove('d-none');
            } else {
                shiftField.classList.add('d-none');
            }
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', toggleShift);
            toggleShift();
        }
    });
</script>
@endpush
@endsection