<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin BPS Satu',
                'email' => 'adminbpssatu@gmail.com',
                'password' => 'password1231',
            ],
            [
                'name' => 'Admin BPS Dua',
                'email' => 'adminbpsdua@gmail.com',
                'password' => 'password1232',
            ],
            [
                'name' => 'Admin BPS Tiga',
                'email' => 'adminbpstiga@gmail.com',
                'password' => 'password1234',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'role' => 'admin',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
