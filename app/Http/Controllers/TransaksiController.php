<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kendaraan;
use App\Models\AreaParkir;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['kendaraan', 'tarif', 'area'])
            ->orderByDesc('id_transaksi');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%$search%")
                  ->orWhere('warna', 'like', "%$search%");
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {
        $query->where('status', $request->status);
        } else {
            // 🔥 default hanya tampil yang aktif
            $query->where('status', 'aktif');
        }
                $transaksis = $query->paginate(10)->withQueryString();
        $areas      = AreaParkir::whereRaw('terisi < kapasitas')->get();

        // hanya kendaraan yang tidak parkir
        $kendaraan  = Kendaraan::where('status', '!=', 'parkir')->get();

        return view('transaksi.index', compact('transaksis', 'areas', 'kendaraan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kendaraan' => 'required|exists:kendaraan,id_kendaraan',
            'id_area'      => 'required|exists:area_parkir,id_area',
        ]);

        $kendaraan = Kendaraan::findOrFail($request->id_kendaraan);
        $tarif     = Tarif::findOrFail($kendaraan->id_tarif);
        $area      = AreaParkir::findOrFail($request->id_area);

        if ($area->terisi >= $area->kapasitas) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Area parkir sudah penuh.');
        }

        if ($kendaraan->status === 'parkir') {
            return redirect()->route('transaksi.index')
                ->with('error', 'Kendaraan ini masih parkir.');
        }

        $kendaraan->update(['status' => 'parkir']);

        Transaksi::create([
            'id_kendaraan' => $kendaraan->id_kendaraan,
            'id_tarif'     => $kendaraan->id_tarif,
            'id_area'      => $request->id_area,
            'waktu_masuk'  => Carbon::now(),
            'waktu_keluar' => null,
            'durasi_jam'   => 0,
            'durasi_menit' => 0,
            'durasi'       => 0,
            'biaya_total'  => 0,
            'status'       => 'aktif',
            'id_user'      => auth()->id(),
        ]);

        $area->increment('terisi');

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi parkir berhasil dibuat.');
    }

public function selesai(Request $request, $id)
{
    $request->validate([
        'metode_pembayaran' => 'required'
    ]);

    $transaksi = Transaksi::with(['kendaraan', 'tarif'])->findOrFail($id);

    if ($transaksi->status === 'selesai') {
        return redirect()->route('transaksi.index')
            ->with('error', 'Transaksi sudah selesai.');
    }

    $tarif       = $transaksi->tarif;
    $waktuMasuk  = Carbon::parse($transaksi->waktu_masuk);
    $waktuKeluar = Carbon::now();

    $totalMenit = $waktuMasuk->diffInMinutes($waktuKeluar);
    $totalJam   = ceil($totalMenit / 60);
    if ($totalJam < 1) $totalJam = 1;

    $biayaTotal = $totalJam * abs($tarif->tarif_per_jam);

    $transaksi->update([
        'waktu_keluar'      => $waktuKeluar,
        'durasi_jam'        => intdiv($totalMenit, 60),
        'durasi_menit'      => $totalMenit % 60,
        'durasi'            => $totalMenit,
        'biaya_total'       => $biayaTotal,
        'metode_pembayaran' => $request->metode_pembayaran,
        'status'            => 'selesai', // 🔥 INI PENTING
    ]);

    // update kendaraan
    if ($transaksi->kendaraan) {
        $transaksi->kendaraan->update(['status' => 'keluar']);
    }

    // update area
    $area = AreaParkir::find($transaksi->id_area);
    if ($area && $area->terisi > 0) {
        $area->decrement('terisi');
    }

    // 🔥 redirect + auto buka struk
    return redirect()->route('transaksi.struk', $id);
}

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status === 'aktif') {
            $area = AreaParkir::find($transaksi->id_area);
            if ($area && $area->terisi > 0) {
                $area->decrement('terisi');
            }

            if ($transaksi->kendaraan) {
                $transaksi->kendaraan->update(['status' => 'keluar']);
            }
        }

        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function struk($id)
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif', 'area', 'user'])->findOrFail($id);
        return view('transaksi.struk', compact('transaksi'));
    }
}