<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => 'Password123',
                'email_verified_at' => now(),
            ]
        );

        if (! $user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}

