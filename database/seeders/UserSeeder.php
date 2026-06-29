<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Test user with avatar
        User::create([
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => Hash::make('test'),
            'role' => 'student',
            'profile_photo' => 'https://i.pravatar.cc/150?img=1',
        ]);
                User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'profile_photo' => 'https://i.pravatar.cc/150?img=1',
        ]);

        // Create 10 random users with avatars
        User::factory(10)->create();
    }
}