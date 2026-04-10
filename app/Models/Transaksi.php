<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}