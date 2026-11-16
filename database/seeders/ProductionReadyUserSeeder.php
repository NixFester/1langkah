<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionReadyUserSeeder extends Seeder
{
    /**
     * Seed the default production staff/admin accounts without dummy or random users.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@email.com',
                'password' => Hash::make('superadmin'),
                'role' => 'superadmin',
                'profile_photo' => null,
                'bio' => 'Platform super administrator with full access',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'profile_photo' => null,
                'bio' => 'Platform administrator',
            ],
            [
                'name' => 'Keuangan',
                'email' => 'keuangan@email.com',
                'password' => Hash::make('keuangan'),
                'role' => 'keuangan',
                'profile_photo' => null,
                'bio' => 'Finance department staff',
            ],
            [
                'name' => 'Marketing',
                'email' => 'marketing@email.com',
                'password' => Hash::make('marketing'),
                'role' => 'marketing',
                'profile_photo' => null,
                'bio' => 'Marketing department staff',
            ],
            [
                'name' => 'Mentor',
                'email' => 'mentor@email.com',
                'password' => Hash::make('mentor'),
                'role' => 'mentor',
                'profile_photo' => null,
                'bio' => 'Course mentor and instructor',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
