<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kendaraan::with(['user', 'tarif']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%$search%")
                  ->orWhere('warna', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kendaraans = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $tarifs     = Tarif::all();
        $users      = User::where('status', 'aktif')->orderBy('name')->get();

        return view('kendaraan.index', compact('kendaraans', 'tarifs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:kendaraan,plat_nomor',
            'warna'      => 'required|string|max:50',
            'status'     => 'required|in:parkir,keluar',
            'id_tarif'   => 'required|exists:tarif,id_tarif',
            'id_user'    => 'nullable|exists:user,id_user',
        ]);

        Kendaraan::create([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'warna'      => $request->warna,
            'status'     => $request->status,
            'id_tarif'   => $request->id_tarif,
            'id_user'    => $request->id_user ?: null,
        ]);

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:kendaraan,plat_nomor,' . $id . ',id_kendaraan',
            'warna'      => 'required|string|max:50',
            'status'     => 'required|in:parkir,keluar',
            'id_tarif'   => 'required|exists:tarif,id_tarif',
            'id_user'    => 'nullable|exists:user,id_user',
        ]);

        $kendaraan->update([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'warna'      => $request->warna,
            'status'     => $request->status,
            'id_tarif'   => $request->id_tarif,
            'id_user'    => $request->id_user ?: null,
        ]);

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->status = $kendaraan->status === 'parkir' ? 'keluar' : 'parkir';
        $kendaraan->save();

        return redirect()->route('kendaraan.index')->with('success', 'Status kendaraan berhasil diubah.');
    }
    
}