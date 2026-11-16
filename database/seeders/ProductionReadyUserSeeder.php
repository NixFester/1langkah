<?php

namespace Database\Seeders;

use App\Models\Mentor;
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
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            if ($user->role === 'mentor') {
                Mentor::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'role' => 'Course Mentor & Instructor',
                        'company' => '1Langkah',
                        'price' => '0',
                        'rating' => 5.0,
                        'sessions_count' => 0,
                        'initials' => strtoupper(substr($user->name, 0, 2)),
                        'color' => '#3b82f6',
                        'expertise' => [],
                        'bio' => $user->bio,
                    ]
                );
            }
        }
    }
}
