<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
            'terms'    => 'accepted',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),

            // 🔥 FIX: jangan pakai "user"
            'role'     => 'petugas', // atau admin kalau kamu mau default lain
            'status'   => 'aktif',
            'shift'    => null,
        ]);

        return redirect()->route('login')->with('success', 'Register berhasil, silakan login');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // ❌ kalau tidak aktif
    if ($user->status !== 'aktif') {
        Auth::logout();
        return back()->withErrors([
            'email' => 'Akun Anda tidak aktif.',
        ]);
    }

    // ✅ SEMUA ROLE MASUK KE 1 ROUTE
    return redirect()->route('dashboard');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}