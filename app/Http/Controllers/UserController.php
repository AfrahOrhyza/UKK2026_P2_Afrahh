<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderByRaw("FIELD(role,'admin','petugas','owner')")
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                                                  ->orWhere('email', 'like', "%{$request->search}%"))
            ->when($request->role,   fn($q) => $q->where('role', $request->role))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:user,email',
            'password' => 'required|string|confirmed',
            'role'     => 'required|in:admin,petugas,owner',
            'status'   => 'required|in:aktif,nonaktif',
        ];

        if ($request->role === 'petugas') {
            $rules['shift'] = 'required|in:pagi,siang,malam';
        }

        $request->validate($rules, [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Role wajib dipilih.',
            'status.required'    => 'Status wajib dipilih.',
            'shift.required'     => 'Shift wajib dipilih untuk petugas.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
            'shift'    => $request->role === 'petugas' ? $request->shift : null,
        ]);

        LogAktivitas::create([
            'user'      => Auth::user()->name ?? 'Administrator',
            'aktivitas' => 'Menambahkan user: ' . $user->name,
            'waktu'     => now(),
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name'     => 'required|string|max:100',
            'email'    => [
                'required', 'email', 'max:100',
                Rule::unique('user', 'email')->ignore($user->id_user, 'id_user'),
            ],
            'password' => 'nullable|string|confirmed',
            'role'     => 'required|in:admin,petugas,owner',
            'status'   => 'required|in:aktif,nonaktif',
            'shift' => 'required_if:role,petugas'
        ];

        if ($request->role === 'petugas') {
            $rules['shift'] = 'required|in:pagi,siang,malam';
        }

        $request->validate($rules, [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Role wajib dipilih.',
            'status.required'    => 'Status wajib dipilih.',
            'shift.required'     => 'Shift wajib dipilih untuk petugas.',
        ]);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'role'   => $request->role,
            'status' => $request->status,
            'shift'  => $request->role === 'petugas' ? $request->shift : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Gunakan update langsung dengan query builder untuk memastikan shift tersimpan
        User::where('id_user', $id)->update($data);

        LogAktivitas::create([
            'user'      => Auth::user()->name,
            'aktivitas' => 'Mengedit user: ' . $user->name,
            'waktu'     => now(),
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === auth()->id()) {
            return redirect()->route('user.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $jumlahKendaraan = \App\Models\Kendaraan::where('id_user', $id)->count();
        if ($jumlahKendaraan > 0) {
            return redirect()->route('user.index')
                ->with('error', "User '{$user->name}' tidak dapat dihapus karena masih memiliki kendaraan.");
        }

        $jumlahTransaksi = \App\Models\Transaksi::where('id_user', $id)->count();
        if ($jumlahTransaksi > 0) {
            return redirect()->route('user.index')
                ->with('error', "User '{$user->name}' tidak dapat dihapus karena masih memiliki transaksi.");
        }

        $namaUser = $user->name;

        LogAktivitas::create([
            'user'      => Auth::user()->name,
            'aktivitas' => 'Menghapus user: ' . $namaUser,
            'waktu'     => now(),
        ]);

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus.');
    }
    
}