<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $query = AreaParkir::query();

        // Filter search
        if ($request->filled('search')) {
            $query->where('nama_area', 'like', '%' . $request->search . '%');
        }

        // Filter ketersediaan
        if ($request->filled('ketersediaan')) {
            if ($request->ketersediaan === 'tersedia') {
                $query->whereRaw('terisi < kapasitas');
            } elseif ($request->ketersediaan === 'penuh') {
                $query->whereRaw('terisi >= kapasitas');
            }
        }

        $areas = $query->paginate(10)->withQueryString();

        return view('admin.area.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|string|max:100|unique:area_parkir,nama_area',
            'kapasitas'  => 'required|integer|min:1',
        ], [
            'nama_area.required' => 'Nama area wajib diisi.',
            'nama_area.unique'   => 'Nama area sudah ada.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.min'      => 'Kapasitas minimal 1.',
        ]);

        AreaParkir::create([
            'nama_area' => $request->nama_area,
            'kapasitas'  => $request->kapasitas,
            'terisi'     => 0,
        ]);

        return redirect()->route('area.index')
            ->with('success', 'Area parkir berhasil ditambahkan.');
    }

    public function update(Request $request, AreaParkir $area)
    {
        $request->validate([
            'nama_area' => [
                'required', 'string', 'max:100',
                Rule::unique('area_parkir', 'nama_area')->ignore($area->id_area, 'id_area'),
            ],
            'kapasitas' => 'required|integer|min:1',
            'terisi'    => 'required|integer|min:0|lte:kapasitas',
        ], [
            'nama_area.required' => 'Nama area wajib diisi.',
            'nama_area.unique'   => 'Nama area sudah ada.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.min'      => 'Kapasitas minimal 1.',
            'terisi.lte'         => 'Jumlah terisi tidak boleh melebihi kapasitas.',
        ]);

        $area->update([
            'nama_area' => $request->nama_area,
            'kapasitas'  => $request->kapasitas,
            'terisi'     => $request->terisi,
        ]);

        return redirect()->route('area.index')
            ->with('success', 'Area parkir berhasil diperbarui.');
    }

    public function destroy(AreaParkir $area)
    {
        $transaksiAktif = \App\Models\Transaksi::where('id_area', $area->id_area)
            ->where('status', 'aktif')
            ->count();

        if ($transaksiAktif > 0) {
            return redirect()->route('area.index')
                ->with('error', "Area '{$area->nama_area}' tidak dapat dihapus karena masih ada {$transaksiAktif} transaksi aktif.");
        }

        $area->delete();

        return redirect()->route('area.index')
            ->with('success', 'Area parkir berhasil dihapus.');
    }
}