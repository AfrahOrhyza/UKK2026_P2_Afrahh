<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table      = 'kendaraan';
    protected $primaryKey = 'id_kendaraan';
    public $timestamps    = false;

    protected $fillable = ['plat_nomor', 'warna', 'status', 'id_tarif', 'id_user'];

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id_tarif');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_kendaraan', 'id_kendaraan');
    }
}