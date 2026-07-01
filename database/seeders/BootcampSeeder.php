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
                'description' => 'Program intensif LIVE untuk membangun aplikasi web end‑to‑end dengan praktek langsung dan mentoring dari Rudi Yesaya.',
                'short_description' => 'Bootcamp intensif Full‑Stack, praktek & mentoring.',
                'mentor_name' => 'Rudi Yesaya',
                'type' => 'online',
                'participants' => 24,
                'start_date' => '2026-06-28 10:00:00',
                'price' => 'Rp 6.500.000',
                'color' => '#667eea',
                'sessions_info' => '7 sesi LIVE via Zoom',
                'mentor_id' => 301,
            ],
            [
                'id' => 102,
                'title' => 'Data Science Bootcamp',
                'description' => 'Pelatihan intensif data science: pembersihan data, feature engineering, model ML, dan deployment dengan studi kasus industri.',
                'short_description' => 'Bootcamp Data Science dengan studi kasus nyata.',
                'mentor_name' => 'Siti Rahayu',
                'type' => 'online',
                'participants' => 18,
                'start_date' => '2026-06-29 14:00:00',
                'price' => 'Rp 7.200.000',
                'color' => '#f093fb',
                'sessions_info' => '8 sesi LIVE via Zoom',
                'mentor_id' => 302,
            ],
            [
                'id' => 103,
                'title' => 'Mobile App Development with Flutter',
                'description' => 'Bangun aplikasi mobile cross‑platform lengkap dengan integrasi Firebase, autentikasi, dan deployment ke store.',
                'short_description' => 'Bootcamp Flutter: dari UI sampai publish.',
                'mentor_name' => 'Budi Santoso',
                'type' => 'online',
                'participants' => 15,
                'start_date' => '2026-07-01 09:00:00',
                'price' => 'Rp 5.900.000',
                'color' => '#4facfe',
                'sessions_info' => '6 sesi LIVE via Zoom',
                'mentor_id' => 303,
            ],
            [
                'id' => 104,
                'title' => 'DevOps & Cloud Bootcamp',
                'description' => 'Praktik containerization, orkestrasi, CI/CD, dan deployment ke cloud untuk kesiapan produksi melalui latihan hands‑on.',
                'short_description' => 'DevOps praktis: Docker, K8s, CI/CD, cloud.',
                'mentor_name' => 'Dewi Lestari',
                'type' => 'online',
                'participants' => 20,
                'start_date' => '2026-06-30 18:30:00',
                'price' => 'Rp 8.000.000',
                'color' => '#43e97b',
                'sessions_info' => '10 sesi LIVE via Zoom',
                'mentor_id' => 304,
            ],
            // Offline
            [
                'id' => 201,
                'title' => 'Laravel Web Developer (Jakarta)',
                'description' => 'Kelas offline intensif Laravel dengan proyek nyata, termasuk deployment, testing, dan best practices untuk developer web.',
                'short_description' => 'Offline Laravel intensif di Jakarta.',
                'mentor_name' => 'Ahmad Fauzi',
                'type' => 'offline',
                'participants' => 12,
                'start_date' => '2026-06-27 09:00:00',
                'price' => 'Rp 4.500.000',
                'color' => '#fa709a',
                'location' => 'Jakarta Pusat, Co‑working Space',
                'mentor_id' => 305,
            ],
            [
                'id' => 202,
                'title' => 'UI/UX Design Sprint (Bandung)',
                'description' => 'Workshop intensif desain: prototyping, user testing, dan presentasi produk untuk menghasilkan solusi yang dapat diuji.',
                'short_description' => 'Sprint desain UI/UX praktis di Bandung.',
                'mentor_name' => 'Budi Santoso',
                'type' => 'offline',
                'participants' => 10,
                'start_date' => '2026-06-29 09:30:00',
                'price' => 'Rp 3.800.000',
                'color' => '#ffecd2',
                'location' => 'Bandung, Creative Hub',
                'mentor_id' => 303,
            ],
            [
                'id' => 203,
                'title' => 'Python & Data Analytics (Surabaya)',
                'description' => 'Kursus offline Python untuk analisis data dan automasi pekerjaan menggunakan dataset nyata dan teknik yang langsung dapat diterapkan.',
                'short_description' => 'Python & analytics praktis di Surabaya.',
                'mentor_name' => 'Siti Rahayu',
                'type' => 'offline',
                'participants' => 14,
                'start_date' => '2026-07-02 13:00:00',
                'price' => 'Rp 5.200.000',
                'color' => '#a1c4fd',
                'location' => 'Surabaya, Tech Center',
                'mentor_id' => 302,
            ],
        ];

        foreach ($bootcamps as $b) {
            $bootcampData = $b;

            // Add new fields for offline bootcamps
            if ($b['type'] === 'offline') {
                $bootcampData['jadwal_kelas'] = json_encode([
                    ['hari' => 'Sabtu', 'jam' => '09:00 - 12:00'],
                    ['hari' => 'Minggu', 'jam' => '13:00 - 16:00'],
                ]);
                $bootcampData['benefits'] = json_encode([
                    'Sertifikat completion',
                    'Materi lengkap (PDF)',
                    'Lunch & coffee break',
                    'Networking dengan peserta lain',
                ]);
                $bootcampData['icon'] = 'graduation-cap'; // Default icon name
            }

            Bootcamp::updateOrCreate(['id' => $bootcampData['id']], $bootcampData);
        }
    }
}