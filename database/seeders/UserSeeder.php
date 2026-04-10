<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user')->insert([
            'name'       => 'Admin',
            'email'      => 'admin@ukk2026.com',
            'password'   => Hash::make('123456'),
            'role'       => 'admin',
            'status'     => '1', // ganti sesuai ENUM tabel kamu
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}