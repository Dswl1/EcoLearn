<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin123@gmail.com',
            'password' => 'admin123',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $this->call(ContentSeeder::class);
    }
}
