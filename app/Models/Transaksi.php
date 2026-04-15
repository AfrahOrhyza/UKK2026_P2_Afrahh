<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Transaksi extends Model
{
    protected $table      = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public    $timestamps = false;

    protected $fillable = [
        'id_kendaraan', 'id_tarif', 'waktu_masuk', 'waktu_keluar',
        'durasi_jam', 'durasi_menit', 'durasi', 'biaya_total',
        'status', 'id_user', 'id_area'
    ];

    public function kendaraan() {
    return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id_kendaraan');
    }
    public function tarif() {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id_tarif');
    }
    public function area() {
        return $this->belongsTo(AreaParkir::class, 'id_area', 'id_area');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function getAreaNamaAttribute()
{
    return json_decode($this->area)->nama_area ?? '-';
}
}