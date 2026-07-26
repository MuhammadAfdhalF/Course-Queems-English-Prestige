<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'role' => 'admin',
            ],
            [
                'name' => 'Queens Admin',
                'email' => 'qeprestige@gmail.com',
                'password' => Hash::make('queens@12345678'),
                'is_active' => true,
            ]
        );
    }
}