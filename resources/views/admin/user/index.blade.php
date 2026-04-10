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
                        <option value="user"    {{ request('role') === 'user'    ? 'selected' : '' }}>User</option>
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

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="text-center pe-3" style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="ps-3 text-muted">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-{{ $user->role_badge }} bg-opacity-10"
                                         style="width:38px;height:38px;min-width:38px;">
                                        <span class="fw-bold text-{{ $user->role_badge }}" style="font-size:14px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">ID: {{ $user->id_user }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge px-2 py-1
                                    @if($user->role === 'admin') bg-danger
                                    @elseif($user->role === 'petugas') bg-warning text-dark
                                    @else bg-primary
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge px-2 py-1 {{ $user->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="text-muted small">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex gap-1 justify-content-center">
                                    {{-- Toggle Status --}}
                                    <form action="{{ route('user.toggle-status', $user->id_user) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $user->status === 'aktif' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            title="{{ $user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi {{ $user->status === 'aktif' ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Tombol Edit (buka modal) --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        title="Edit"
                                        onclick="bukaModalEdit(
                                            {{ $user->id_user }},
                                            '{{ addslashes($user->name) }}',
                                            '{{ $user->email }}',
                                            '{{ $user->role }}',
                                            '{{ $user->status }}'
                                        )">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- Tombol Hapus (buka modal) --}}
                                    @if($user->id_user !== auth()->id())
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        title="Hapus"
                                        onclick="bukaModalHapus({{ $user->id_user }}, '{{ addslashes($user->name) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                                Tidak ada data user ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->hasPages())
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
            <small class="text-muted">
                Menampilkan {{ $user->firstItem() }}–{{ $user->lastItem() }} dari {{ $user->total() }} user
            </small>
            {{ $user->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>


{{-- ===================== MODAL TAMBAH USER ===================== --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="labelTambah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('user.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <input type="hidden" name="_from_modal" value="tambah"> 
                    <h5 class="modal-title fw-bold" id="labelTambah">
                        <i class="bi bi-person-plus me-2 text-primary"></i>Tambah User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="contoh@email.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Password --}}
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
                    {{-- Konfirmasi Password --}}
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
                    {{-- Role & Status --}}
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                                <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                                <option value="user"    {{ old('role') === 'user'    ? 'selected' : '' }}>User</option>
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
                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control"
                            placeholder="Masukkan nama lengkap" required>
                    </div>
                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="edit_email" class="form-control"
                            placeholder="contoh@email.com" required>
                    </div>
                    {{-- Password --}}
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
                    {{-- Konfirmasi Password --}}
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
                    {{-- Role & Status --}}
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role" id="edit_role" class="form-select" required>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
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

@push('scripts')
<script>
    function bukaModalEdit(id, nama, email, role, status) {
        document.getElementById('formEdit').action = '/user/' + id;
        document.getElementById('edit_name').value   = nama;
        document.getElementById('edit_email').value  = email;
        document.getElementById('edit_role').value   = role;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirm').value = '';
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    function bukaModalHapus(id, nama) {
        document.getElementById('formHapus').action = '/user/' + id;
        document.getElementById('hapusNamaUser').textContent = nama;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    }

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

    @if($errors->any() && old('_from_modal') === 'tambah')
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('modalTambah')).show();
        });
    @endif
</script>
@endpush
@endsection