<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table      = 'log_aktivitas';
    protected $primaryKey = 'id_log';
    public $timestamps    = false;

    protected $fillable = [
        'id_user',
        'aktivitas',
        'waktu_aktivitas',
    ];

    protected $casts = [
        'waktu_aktivitas' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Helper static: catat log dari mana saja
    public static function catat(string $aktivitas, int $idUser = null): void
    {
        self::create([
            'id_user'         => $idUser ?? auth()->id(),
            'aktivitas'       => $aktivitas,
            'waktu_aktivitas' => now(),
        ]);
    }
}