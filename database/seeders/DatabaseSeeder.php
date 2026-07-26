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
                'email' => 'qeprestige@gmail.com',
            ],
            [
                'name' => 'Queens Admin',
                'password' => 'queens@12345678',
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}