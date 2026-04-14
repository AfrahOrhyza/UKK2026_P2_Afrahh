<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Transaksi;
use App\Models\AreaParkir;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $role  = auth()->user()->role;
        $today = Carbon::today();

        // ================= ADMIN =================
        if ($role == 'admin') {

            return view('admin.index', [
                'totalUser'        => User::count(),
                'totalKendaraan'   => Kendaraan::count(),
                'totalArea'        => AreaParkir::count(),
                'transaksiAktif'   => Transaksi::where('status', 'aktif')->count(),
                'masukHariIni'     => Transaksi::whereDate('waktu_masuk', $today)->count(),
                'keluarHariIni'    => Transaksi::whereDate('waktu_keluar', $today)->count(),

                // 🔥 TAMBAHAN: transaksi terbaru (AMAN TANPA created_at)
                'transaksiTerbaru' => Transaksi::with(['kendaraan', 'area'])
                    ->latest('waktu_masuk')
                    ->take(5)
                    ->get(),
            ]);
        }

        // ================= PETUGAS =================
        if ($role == 'petugas') {

            return view('petugas.index', [
                'masukHariIni'  => Transaksi::whereDate('waktu_masuk', $today)->count(),
                'keluarHariIni' => Transaksi::whereDate('waktu_keluar', $today)->count(),

                // 🔥 TAMBAHAN
                'transaksiTerbaru' => Transaksi::with(['kendaraan', 'area'])
                    ->whereDate('waktu_masuk', $today)
                    ->latest('waktu_masuk')
                    ->take(5)
                    ->get(),
            ]);
        }

        // ================= OWNER =================
        if ($role == 'owner') {

            return view('owner.index', [
                'totalUser'      => User::count(),
                'totalKendaraan' => Kendaraan::count(),
                'totalArea'      => AreaParkir::count(),

                // 🔥 OPTIONAL juga boleh pakai
                'transaksiTerbaru' => Transaksi::with(['kendaraan', 'area'])
                    ->latest('waktu_masuk')
                    ->take(5)
                    ->get(),
            ]);
        }

        abort(403);
    }
}