<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@issm.org.br'],
            [
                'name' => 'Administrador ISSM',
                'email' => 'admin@issm.org.br',
                'password' => Hash::make('Admin@ISSM2024!'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );
    }
}
