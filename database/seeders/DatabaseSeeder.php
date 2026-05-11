<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'Queens Admin',
                'password' => 'admin12345678',
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}