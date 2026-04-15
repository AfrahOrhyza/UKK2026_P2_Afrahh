<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        if ($request->dari && $request->sampai) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ]);
        }

        $riwayat = $query->orderBy('waktu_masuk', 'desc')->paginate(10);

        return view('owner.riwayat', compact('riwayat'));
    }
}