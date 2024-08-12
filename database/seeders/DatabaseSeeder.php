<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'MMD Tim 7', 'username' => 'MMDTim7', 'password' => bcrypt('MMDTim7Filkom')],
            ['name' => 'Admin', 'username' => 'Admin', 'password' => bcrypt('Adm1n!2024')],
            ['name' => 'Admin1', 'username' => 'Admin1', 'password' => bcrypt('Adm1n1!2024')],
            ['name' => 'Admin2', 'username' => 'Admin2', 'password' => bcrypt('Adm1n2!2024')],
            ['name' => 'Admin3', 'username' => 'Admin3', 'password' => bcrypt('Adm1n3!2024')],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );
        }
    }
}
