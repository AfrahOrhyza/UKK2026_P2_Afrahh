<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    protected $table = 'area_parkir';
    protected $primaryKey = 'id_area';
    public $timestamps = false;

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'terisi',
    ];

    // Relasi ke transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_area', 'id_area');
    }

    // Accessor: slot tersedia
    public function getSisaAttribute()
    {
        return $this->kapasitas - $this->terisi;
    }

    // Accessor: persentase terisi
    public function getPersentaseAttribute()
    {
        if ($this->kapasitas == 0) return 0;
        return round(($this->terisi / $this->kapasitas) * 100);
    }
}