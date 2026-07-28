<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Kopi Ancua',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password123'), 
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'kasir',
        ]);

        $this->call([
            MenuSeeder::class,
        ]);
    }
}
