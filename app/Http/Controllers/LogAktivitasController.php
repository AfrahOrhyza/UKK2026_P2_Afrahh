<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('waktu_aktivitas', 'desc');

        // Filter search aktivitas
        if ($request->filled('search')) {
            $query->where('aktivitas', 'like', '%' . $request->search . '%');
        }

        // Filter by user
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }

        // Filter by tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_aktivitas', $request->tanggal);
        }

        $logs  = $query->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('admin.log.index', compact('logs', 'users'));
    }

    public function destroy($id)
    {
        $log = LogAktivitas::findOrFail($id);
        $log->delete();

        return redirect()->route('log.index')
            ->with('success', 'Log aktivitas berhasil dihapus.');
    }

    public function destroyAll()
    {
        LogAktivitas::truncate();

        return redirect()->route('log.index')
            ->with('success', 'Semua log aktivitas berhasil dihapus.');
    }
}