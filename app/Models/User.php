<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table      = 'user';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'shift',  // ← ditambahkan
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: User memiliki banyak kendaraan
     */
    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class, 'id_user', 'id_user');
    }

    /**
     * Relasi: User memiliki banyak transaksi
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_user', 'id_user');
    }

    /**
     * Relasi: User memiliki banyak log aktivitas
     */
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'id_user', 'id_user');
    }

    /**
     * Relasi: User memiliki banyak riwayat
     */
    public function riwayats()
    {
        return $this->hasMany(Riwayat::class, 'id_user', 'id_user');
    }

    /**
     * Badge warna role
     */
    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'admin'   => 'danger',
            'petugas' => 'warning',
            'user'    => 'info',
            default   => 'secondary',
        };
    }

    /**
     * Badge warna status
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'aktif' ? 'success' : 'secondary';
    }
}