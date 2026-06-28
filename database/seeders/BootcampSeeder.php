<?php

namespace Database\Seeders;

use App\Models\Bootcamp;
use Illuminate\Database\Seeder;

class BootcampSeeder extends Seeder
{
    public function run(): void
    {
        $bootcamps = [
            // Online
            [
                'id' => 101,
                'title' => 'Full‑Stack Web Development Bootcamp',
                'mentor_name' => 'Rudi Yesaya',
                'type' => 'online',
                'participants' => 24,
                'start_date' => '11 Agu 2025',
                'price' => 'Rp 6.500.000',
                'color' => '#667eea',
                'sessions_info' => '7 sesi LIVE via Zoom',
                'mentor_id' => 301,
            ],
            [
                'id' => 102,
                'title' => 'Data Science Bootcamp',
                'mentor_name' => 'Siti Rahayu',
                'type' => 'online',
                'participants' => 18,
                'start_date' => '18 Agu 2025',
                'price' => 'Rp 7.200.000',
                'color' => '#f093fb',
                'sessions_info' => '8 sesi LIVE via Zoom',
                'mentor_id' => 302,
            ],
            [
                'id' => 103,
                'title' => 'Mobile App Development with Flutter',
                'mentor_name' => 'Budi Santoso',
                'type' => 'online',
                'participants' => 15,
                'start_date' => '25 Agu 2025',
                'price' => 'Rp 5.900.000',
                'color' => '#4facfe',
                'sessions_info' => '6 sesi LIVE via Zoom',
                'mentor_id' => 303,
            ],
            [
                'id' => 104,
                'title' => 'DevOps & Cloud Bootcamp',
                'mentor_name' => 'Dewi Lestari',
                'type' => 'online',
                'participants' => 20,
                'start_date' => '1 Sep 2025',
                'price' => 'Rp 8.000.000',
                'color' => '#43e97b',
                'sessions_info' => '10 sesi LIVE via Zoom',
                'mentor_id' => 304,
            ],
            // Offline
            [
                'id' => 201,
                'title' => 'Laravel Web Developer (Jakarta)',
                'mentor_name' => 'Ahmad Fauzi',
                'type' => 'offline',
                'participants' => 12,
                'start_date' => '10 Agu 2025',
                'price' => 'Rp 4.500.000',
                'color' => '#fa709a',
                'location' => 'Jakarta Pusat, Co‑working Space',
                'mentor_id' => 305,
            ],
            [
                'id' => 202,
                'title' => 'UI/UX Design Sprint (Bandung)',
                'mentor_name' => 'Budi Santoso',
                'type' => 'offline',
                'participants' => 10,
                'start_date' => '17 Agu 2025',
                'price' => 'Rp 3.800.000',
                'color' => '#ffecd2',
                'location' => 'Bandung, Creative Hub',
                'mentor_id' => 303,
            ],
            [
                'id' => 203,
                'title' => 'Python & Data Analytics (Surabaya)',
                'mentor_name' => 'Siti Rahayu',
                'type' => 'offline',
                'participants' => 14,
                'start_date' => '24 Agu 2025',
                'price' => 'Rp 5.200.000',
                'color' => '#a1c4fd',
                'location' => 'Surabaya, Tech Center',
                'mentor_id' => 302,
            ],
        ];

        foreach ($bootcamps as $b) {
            Bootcamp::create($b);
        }
    }
}