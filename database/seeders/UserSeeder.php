<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Define all users with their roles
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@email.com',
                'password' => Hash::make('superadmin'),
                'role' => 'superadmin',
                'profile_photo' => 'https://i.pravatar.cc/150?img=10',
                'bio' => 'Platform super administrator with full access',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'profile_photo' => 'https://i.pravatar.cc/150?img=2',
                'bio' => 'Platform administrator',
            ],
            [
                'name' => 'Keuangan',
                'email' => 'keuangan@email.com',
                'password' => Hash::make('keuangan'),
                'role' => 'keuangan',
                'profile_photo' => 'https://i.pravatar.cc/150?img=5',
                'bio' => 'Finance department staff',
            ],
            [
                'name' => 'Marketing',
                'email' => 'marketing@email.com',
                'password' => Hash::make('marketing'),
                'role' => 'marketing',
                'profile_photo' => 'https://i.pravatar.cc/150?img=9',
                'bio' => 'Marketing department staff',
            ],
            [
                'name' => 'Mentor',
                'email' => 'mentor@email.com',
                'password' => Hash::make('mentor'),
                'role' => 'mentor',
                'profile_photo' => 'https://i.pravatar.cc/150?img=3',
                'bio' => 'Course mentor and instructor',
            ],
            [
                'name' => 'Test User',
                'email' => 'test@email.com',
                'password' => Hash::make('test'),
                'role' => 'student',
                'profile_photo' => 'https://i.pravatar.cc/150?img=1',
                'bio' => 'Passionate learner exploring web development and data science. Always eager to learn new technologies!',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Create 10 random users with avatars (only if needed)
        if (User::count() <= 6) {
            User::factory(10)->create();
        }
    }
}