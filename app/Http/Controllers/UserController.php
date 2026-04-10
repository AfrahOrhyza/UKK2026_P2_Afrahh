<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of user.
     */
  public function index()
{
    $users = \App\Models\User::paginate(10);
    return view('admin.user.index', compact('users'));
}


    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:user,email',
            'password' => 'required|string|confirmed',
            'role'     => 'required|in:admin,petugas,user',
            'status'   => 'required|in:aktif,nonaktif',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Role wajib dipilih.',
            'status.required'    => 'Status wajib dipilih.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', 'max:100', Rule::unique('user', 'email')->ignore($user->id_user, 'id_user')],
           'password' => 'required|string|confirmed',
            'role'     => 'required|in:admin,petugas,user',
            'status'   => 'required|in:aktif,nonaktif',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password' => 'required|string|confirmed',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Role wajib dipilih.',
            'status.required'    => 'Status wajib dipilih.',
        ]);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'role'   => $request->role,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
{
    $user = User::findOrFail($id);

    // Cegah hapus akun sendiri
    if ($user->id_user === auth()->id()) {
        return redirect()->route('user.index')
            ->with('error', 'Tidak dapat menghapus akun sendiri.');
    }

    // Cek apakah user masih punya kendaraan terdaftar
    $jumlahKendaraan = \App\Models\Kendaraan::where('id_user', $id)->count();
    if ($jumlahKendaraan > 0) {
        return redirect()->route('user.index')
            ->with('error', "User '{$user->name}' tidak dapat dihapus karena masih memiliki {$jumlahKendaraan} kendaraan terdaftar.");
    }

    // Cek apakah user masih punya transaksi
    $jumlahTransaksi = \App\Models\Transaksi::where('id_user', $id)->count();
    if ($jumlahTransaksi > 0) {
        return redirect()->route('user.index')
            ->with('error', "User '{$user->name}' tidak dapat dihapus karena masih memiliki riwayat transaksi.");
    }

    $user->delete();

    return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
}

    /**
     * Toggle user status aktif/nonaktif.
     */
    public function toggleStatus($id_user)
{
    $user = User::findOrFail($id_user);
    $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
    $user->save();

    return back()->with('success', 'Status user berhasil diubah.');
}
}