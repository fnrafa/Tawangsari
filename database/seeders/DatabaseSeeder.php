<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'MMD Tim 7',
            'username' => 'MMDTim7',
            'password' => bcrypt('MMDTim7Filkom'),
        ]);
    }
}
