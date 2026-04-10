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
        $totalUser        = User::count();
        $totalKendaraan   = Kendaraan::count();
        $masuk            = Transaksi::whereDate('waktu_masuk', Carbon::today())->count();
        $keluar           = Transaksi::whereDate('waktu_keluar', Carbon::today())->count();
        $totalArea        = AreaParkir::count();
        $transaksiAktif   = Transaksi::where('status', 'aktif')->count();
        $transaksiTerbaru = Transaksi::with(['kendaraan.tarif', 'area'])
                            ->latest('waktu_masuk')
                            ->take(5)
                            ->get();

        return view('admin.index', compact(
            'totalUser', 'totalKendaraan', 'masuk', 'keluar',
            'totalArea', 'transaksiAktif', 'transaksiTerbaru'
        ));
    }
}