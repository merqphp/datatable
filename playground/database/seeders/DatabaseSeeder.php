<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Ada Lovelace', 'email' => 'ada@example.com'],
            ['name' => 'Grace Hopper', 'email' => 'grace@example.com'],
            ['name' => 'Margaret Hamilton', 'email' => 'margaret@example.com'],
        ] as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [...$user, 'password' => 'password'],
            );
        }
    }
}
