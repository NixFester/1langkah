<?php

namespace Database\Seeders;

use App\Models\Mentor;
use Illuminate\Database\Seeder;

class MentorSeeder extends Seeder
{
    public function run(): void
    {
        $mentors = [
            [
                'id' => 301,
                'name' => 'Rudi Yesaya',
                'role' => 'Senior Software Engineer',
                'company' => 'Google',
                'price' => 'Rp 150.000/sesi',
                'rating' => 4.9,
                'sessions_count' => 340,
                'initials' => 'RY',
                'color' => '#667eea',
                'expertise' => ['React', 'Node.js', 'TypeScript', 'System Design'],
                'bio' => '10+ tahun pengalaman di Google, lulusan UI.',
            ],
            [
                'id' => 302,
                'name' => 'Siti Rahayu',
                'role' => 'Lead Data Scientist',
                'company' => 'Tokopedia',
                'price' => 'Rp 200.000/sesi',
                'rating' => 4.8,
                'sessions_count' => 210,
                'initials' => 'SR',
                'color' => '#f093fb',
                'expertise' => ['Python', 'Machine Learning', 'SQL', 'Tableau'],
                'bio' => 'Praktisi data science dengan fokus pada e‑commerce.',
            ],
            [
                'id' => 303,
                'name' => 'Budi Santoso',
                'role' => 'CTO & Co‑founder',
                'company' => 'Startup Edukasi',
                'price' => 'Rp 180.000/sesi',
                'rating' => 4.7,
                'sessions_count' => 180,
                'initials' => 'BS',
                'color' => '#4facfe',
                'expertise' => ['Flutter', 'Firebase', 'UI/UX', 'Agile'],
                'bio' => 'Pernah membangun 3 startup di bidang pendidikan.',
            ],
            [
                'id' => 304,
                'name' => 'Dewi Lestari',
                'role' => 'DevOps Engineer',
                'company' => 'Gojek',
                'price' => 'Rp 170.000/sesi',
                'rating' => 4.9,
                'sessions_count' => 290,
                'initials' => 'DL',
                'color' => '#43e97b',
                'expertise' => ['Docker', 'Kubernetes', 'CI/CD', 'AWS'],
                'bio' => 'Spesialis infrastruktur cloud dan automation.',
            ],
            [
                'id' => 305,
                'name' => 'Ahmad Fauzi',
                'role' => 'Full‑Stack Developer',
                'company' => 'Freelance',
                'price' => 'Rp 140.000/sesi',
                'rating' => 4.6,
                'sessions_count' => 150,
                'initials' => 'AF',
                'color' => '#fa709a',
                'expertise' => ['Vue.js', 'Laravel', 'PHP', 'Tailwind'],
                'bio' => 'Pengembang lepas dengan 8 tahun pengalaman.',
            ],
        ];

        foreach ($mentors as $m) {
            $mentorData = $m;
            $mentorData['linkedin_url'] = 'https://linkedin.com/in/' . strtolower(str_replace(' ', '-', $m['name']));
            Mentor::create($mentorData);
        }
    }
}