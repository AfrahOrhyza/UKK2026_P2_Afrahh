<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    protected $table      = 'riwayat';
    protected $primaryKey = 'id_riwayat';
    public $timestamps    = false;

    protected $fillable = [
        'id_user', 'id_transaksi', 'plat_kendaraan', 'jenis_kendaraan',
        'nama_area', 'waktu_masuk', 'waktu_keluar', 'durasi',
        'biaya_total', 'uang_dibayar', 'kembalian',
        'status_pembayaran', 'metode_pembayaran',
    ];

    protected $casts = [
        'waktu_masuk'  => 'datetime',
        'waktu_keluar' => 'datetime',
        'biaya_total'  => 'decimal:2',
        'uang_dibayar' => 'decimal:2',
        'kembalian'    => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }
}