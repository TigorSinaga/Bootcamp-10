<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('Password'),
                'role' => 'admin'
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'password' => Hash::make('Password'),
                'role' => 'customer'
            ]
        ];

        foreach ($users as $user) {
            if (!\App\Models\User::where('email', $user['email'])->exists()) {
                \App\Models\User::create($user);
            }
        }
    }
}
