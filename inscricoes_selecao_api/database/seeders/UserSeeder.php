<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@test.com',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Juelson Soxi',
                'email' => 'soxi@test.com',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Moisés Borracha',
                'email' => 'moises@test.com',
                'password' => Hash::make('123456'),
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
