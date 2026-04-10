<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index()
    {
        $tarifs = Tarif::paginate(10);
        return view('tarif.index', compact('tarifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:50|unique:tarif,jenis_kendaraan',
            'tarif_per_jam'   => 'required|numeric|min:0',
        ]);

        Tarif::create($request->only(['jenis_kendaraan', 'tarif_per_jam']));

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $tarif = Tarif::findOrFail($id);

        $request->validate([
            'jenis_kendaraan' => 'required|string|max:50|unique:tarif,jenis_kendaraan,' . $id . ',id_tarif',
            'tarif_per_jam'   => 'required|numeric|min:0',
        ]);

        $tarif->update($request->only(['jenis_kendaraan', 'tarif_per_jam']));

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil diupdate.');
    }

   public function destroy($id)
{
    $tarif = Tarif::findOrFail($id);

    // Cek apakah tarif masih digunakan oleh kendaraan
    $jumlahKendaraan = \App\Models\Kendaraan::where('id_tarif', $id)->count();

    if ($jumlahKendaraan > 0) {
        return redirect()->route('tarif.index')
            ->with('error', "Tarif '{$tarif->jenis_kendaraan}' tidak dapat dihapus karena masih digunakan oleh {$jumlahKendaraan} kendaraan.");
    }

    $tarif->delete();

    return redirect()->route('tarif.index')->with('success', 'Tarif berhasil dihapus.');
}
}