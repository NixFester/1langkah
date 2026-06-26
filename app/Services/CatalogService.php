<?php

namespace App\Services;

/**
 * CatalogService
 *
 * Single source of truth for all demo data shown across the 1Langkah app.
 * Mirrors the original `DATA` constant from index.html so the Laravel port
 * renders identical content to the original showcase.
 */
class CatalogService
{
    public function user(): array
    {
        return [
            'name'         => 'Atta Ul Karim',
            'initials'     => 'AK',
            'role'         => 'Full-Stack Dev',
            'xp'           => '1,240 XP',
            'streak'       => 12,
            'careerReady'  => 76,
        ];
    }

    public function courses(): array
    {
        return [
            ['id' => 1, 'title' => 'Full-Stack Web Development Bootcamp', 'mentor' => 'Rudi Yesaya', 'mentorCompany' => 'Google', 'category' => 'Programming', 'level' => 'Intermediate', 'badge' => 'Bestseller', 'rating' => 4.9, 'students' => 12400, 'price' => 'Rp 799.000', 'progress' => 68, 'color' => '#667eea'],
            ['id' => 2, 'title' => 'AI & Machine Learning Fundamentals', 'mentor' => 'Andi Wijaya', 'mentorCompany' => 'Gojek', 'category' => 'Data Science', 'level' => 'Advanced', 'rating' => 4.8, 'students' => 8900, 'price' => 'Rp 899.000', 'progress' => 23, 'color' => '#764ba2'],
            ['id' => 3, 'title' => 'Data Science with Python', 'mentor' => 'Fajar Hidayat', 'mentorCompany' => 'Tokopedia', 'category' => 'Data Science', 'level' => 'Intermediate', 'rating' => 4.7, 'students' => 6500, 'price' => 'Rp 699.000', 'progress' => 45, 'color' => '#f5576c'],
            ['id' => 4, 'title' => 'UI/UX Design Mastery', 'mentor' => 'Sari Dewi', 'mentorCompany' => 'Tokopedia', 'category' => 'Design', 'level' => 'Beginner', 'rating' => 4.9, 'students' => 15200, 'price' => 'Rp 499.000', 'progress' => 0, 'color' => '#4facfe'],
            ['id' => 5, 'title' => 'Digital Marketing Strategy', 'mentor' => 'Rina Kusuma', 'mentorCompany' => 'Shopee', 'category' => 'Marketing', 'level' => 'Beginner', 'rating' => 4.6, 'students' => 9800, 'price' => 'Rp 399.000', 'progress' => 0, 'color' => '#43e97b'],
            ['id' => 6, 'title' => 'Leadership & Management Excellence', 'mentor' => 'Dewi Rahayu', 'mentorCompany' => 'BCA', 'category' => 'Business', 'level' => 'Intermediate', 'rating' => 4.7, 'students' => 5400, 'price' => 'Rp 599.000', 'progress' => 0, 'color' => '#fa709a'],
            ['id' => 7, 'title' => 'React & Next.js Advanced Patterns', 'mentor' => 'Rudi Yesaya', 'mentorCompany' => 'Google', 'category' => 'Programming', 'level' => 'Advanced', 'rating' => 4.8, 'students' => 7200, 'price' => 'Rp 699.000', 'progress' => 0, 'color' => '#a18cd1'],
            ['id' => 8, 'title' => 'Cloud Architecture with AWS', 'mentor' => 'Budi Santoso', 'mentorCompany' => 'Traveloka', 'category' => 'Cloud', 'level' => 'Advanced', 'rating' => 4.6, 'students' => 3200, 'price' => 'Rp 999.000', 'progress' => 0, 'color' => '#ff9a3c'],
            ['id' => 9, 'title' => 'Cybersecurity Fundamentals', 'mentor' => 'Hendra Wijaya', 'mentorCompany' => 'Pertamina', 'category' => 'Security', 'level' => 'Beginner', 'rating' => 4.5, 'students' => 4100, 'price' => 'Rp 549.000', 'progress' => 0, 'color' => '#0cebeb'],
        ];
    }

    public function course(int $id): ?array
    {
        foreach ($this->courses() as $course) {
            if ($course['id'] === $id) {
                return $course;
            }
        }
        return null;
    }

    public function chapters(): array
    {
        return [
            ['title' => 'Pengenalan & Setup Environment', 'lessons' => 5, 'duration' => '2h 30m'],
            ['title' => 'Fundamentals: HTML, CSS, JavaScript', 'lessons' => 12, 'duration' => '6h 15m'],
            ['title' => 'React & State Management', 'lessons' => 10, 'duration' => '5h 45m'],
            ['title' => 'Backend with Node.js & Express', 'lessons' => 8, 'duration' => '4h 20m'],
            ['title' => 'Database & API Design', 'lessons' => 7, 'duration' => '3h 50m'],
            ['title' => 'Deployment & Best Practices', 'lessons' => 6, 'duration' => '3h 10m'],
        ];
    }

    public function bootcamps(): array
    {
        return [
            'online' => [
                ['id' => 101, 'title' => 'Full-Stack Web Development Bootcamp', 'mentor' => 'Rudi Yesaya', 'participants' => 24, 'startDate' => '11 Agu 2025', 'sessions' => '7 sesi LIVE via Zoom', 'price' => 'Rp 6.5jt', 'color' => '#667eea'],
                ['id' => 102, 'title' => 'Data Science Intensive', 'mentor' => 'Andi Wijaya', 'participants' => 18, 'startDate' => '25 Agu 2025', 'sessions' => '10 sesi LIVE via Zoom', 'price' => 'Rp 7.5jt', 'color' => '#764ba2'],
                ['id' => 103, 'title' => 'UI/UX Design Sprint', 'mentor' => 'Sari Dewi', 'participants' => 15, 'startDate' => '1 Sep 2025', 'sessions' => '7 sesi LIVE via Zoom', 'price' => 'Rp 5.5jt', 'color' => '#f5576c'],
            ],
            'offline' => [
                ['id' => 201, 'title' => 'Full-Stack Immersive Bootcamp', 'mentor' => 'Rudi Yesaya', 'participants' => 20, 'startDate' => '15 Sep 2025', 'location' => 'Jakarta', 'price' => 'Rp 12jt', 'color' => '#fa709a'],
                ['id' => 202, 'title' => 'Data Engineering Bootcamp', 'mentor' => 'Andi Wijaya', 'participants' => 16, 'startDate' => '20 Okt 2025', 'location' => 'Bandung', 'price' => 'Rp 10jt', 'color' => '#43e97b'],
                ['id' => 203, 'title' => 'Mobile Development Bootcamp', 'mentor' => 'Budi Santoso', 'participants' => 22, 'startDate' => '10 Nov 2025', 'location' => 'Surabaya', 'price' => 'Rp 11jt', 'color' => '#4facfe'],
            ],
        ];
    }

    public function onlineBootcamp(int $id): ?array
    {
        foreach ($this->bootcamps()['online'] as $b) {
            if ($b['id'] === $id) {
                return $b;
            }
        }
        return null;
    }

    public function offlineBootcamp(int $id): ?array
    {
        foreach ($this->bootcamps()['offline'] as $b) {
            if ($b['id'] === $id) {
                return $b;
            }
        }
        return null;
    }

    public function onlineSessions(): array
    {
        return [
            ['date' => '11 Agu 2025', 'topic' => 'Introduction & Environment Setup', 'time' => '10:00 - 12:00 WIB'],
            ['date' => '14 Agu 2025', 'topic' => 'HTML, CSS & Responsive Design', 'time' => '10:00 - 12:00 WIB'],
            ['date' => '18 Agu 2025', 'topic' => 'JavaScript Fundamentals', 'time' => '10:00 - 13:00 WIB'],
            ['date' => '21 Agu 2025', 'topic' => 'React Basics & Components', 'time' => '10:00 - 12:00 WIB'],
            ['date' => '25 Agu 2025', 'topic' => 'State Management & API', 'time' => '10:00 - 13:00 WIB'],
            ['date' => '28 Agu 2025', 'topic' => 'Node.js & Backend', 'time' => '10:00 - 12:00 WIB'],
            ['date' => '1 Sep 2025', 'topic' => 'Final Project & Deployment', 'time' => '10:00 - 13:00 WIB'],
        ];
    }

    public function offlineFeatures(): array
    {
        return [
            '10 hari intensif tatap muka',
            'Mentor 1-on-1 setiap hari',
            'Project portofolio nyata',
            'Sertifikat completion',
            'Akses komunitas alumni',
            'Networking dengan sesama peserta',
            'Career coaching session',
            'Priority job referral',
        ];
    }

    public function mentors(): array
    {
        return [
            ['id' => 301, 'name' => 'Rudi Yesaya', 'role' => 'Senior Software Engineer', 'company' => 'Google', 'price' => 'Rp 150.000/sesi', 'rating' => 4.9, 'sessions' => 340, 'initials' => 'RY', 'color' => '#667eea', 'expertise' => ['React', 'Node.js', 'TypeScript', 'System Design'], 'bio' => '10+ tahun pengalaman di Google. Spesialis dalam full-stack development dan system design. Telah membimbing 300+ profesional IT.'],
            ['id' => 302, 'name' => 'Sari Dewi', 'role' => 'Lead Product Designer', 'company' => 'Tokopedia', 'price' => 'Rp 120.000/sesi', 'rating' => 4.8, 'sessions' => 280, 'initials' => 'SD', 'color' => '#f5576c', 'expertise' => ['UI/UX Design', 'Figma', 'Design System', 'User Research'], 'bio' => 'Mendesain produk untuk 100M+ pengguna di Tokopedia. Expert dalam design thinking dan user-centered design.'],
            ['id' => 303, 'name' => 'Andi Wijaya', 'role' => 'ML Engineer', 'company' => 'Gojek', 'price' => 'Rp 200.000/sesi', 'rating' => 4.9, 'sessions' => 195, 'initials' => 'AW', 'color' => '#764ba2', 'expertise' => ['Machine Learning', 'Python', 'TensorFlow', 'Data Science'], 'bio' => 'Membangun model ML untuk 50M+ transaksi harian di Gojek. PhD dari NUS Singapore.'],
            ['id' => 304, 'name' => 'Rina Kusuma', 'role' => 'Head of Marketing', 'company' => 'Shopee', 'price' => 'Rp 100.000/sesi', 'rating' => 4.7, 'sessions' => 210, 'initials' => 'RK', 'color' => '#43e97b', 'expertise' => ['Digital Marketing', 'Growth Hacking', 'SEO/SEM', 'Analytics'], 'bio' => 'Mengelola budget marketing >Rp 100M/bulan. Specialist dalam performance marketing dan growth strategy.'],
            ['id' => 305, 'name' => 'Budi Santoso', 'role' => 'Principal Engineer', 'company' => 'Traveloka', 'price' => 'Rp 175.000/sesi', 'rating' => 4.8, 'sessions' => 156, 'initials' => 'BS', 'color' => '#4facfe', 'expertise' => ['Cloud', 'AWS', 'DevOps', 'Microservices'], 'bio' => 'Architect di balik infra Traveloka yang melayani 100M+ booking. AWS Solutions Architect certified.'],
            ['id' => 306, 'name' => 'Dewi Rahayu', 'role' => 'VP of People', 'company' => 'BCA', 'price' => 'Rp 130.000/sesi', 'rating' => 4.7, 'sessions' => 180, 'initials' => 'DR', 'color' => '#fa709a', 'expertise' => ['Leadership', 'Management', 'HR Strategy', 'Team Building'], 'bio' => '15+ tahun di bidang HR dan leadership development. Certified executive coach dari ICF.'],
        ];
    }

    public function mentor(int $id): ?array
    {
        foreach ($this->mentors() as $m) {
            if ($m['id'] === $id) {
                return $m;
            }
        }
        return null;
    }

    public function leaderboard(): array
    {
        return [
            ['name' => 'Ahmad Fauzi', 'xp' => '12,480 XP', 'rank' => 1, 'initials' => 'AF'],
            ['name' => 'Siti Rahma', 'xp' => '11,920 XP', 'rank' => 2, 'initials' => 'SR'],
            ['name' => 'Dito Pratama', 'xp' => '10,750 XP', 'rank' => 3, 'initials' => 'DP'],
            ['name' => 'You (Reza)', 'xp' => '9,840 XP', 'rank' => 4, 'initials' => 'AK', 'isMe' => true],
            ['name' => 'Maya Sari', 'xp' => '8,920 XP', 'rank' => 5, 'initials' => 'MS'],
        ];
    }

    public function activities(): array
    {
        return [
            ['text' => 'Completed Lesson 12: React Hooks', 'time' => '2 hours ago', 'color' => '#10b981'],
            ['text' => 'Earned badge: Streak Master', 'time' => 'Yesterday', 'color' => '#ffb900'],
            ['text' => 'Submitted assignment: UI Design Challenge', 'time' => '2 days ago', 'color' => '#3b82f6'],
            ['text' => 'Replied to community post', 'time' => '3 days ago', 'color' => '#8b5cf6'],
            ['text' => 'Received certificate: Data Science', 'time' => '1 week ago', 'color' => '#d10000'],
        ];
    }

    public function testimonials(): array
    {
        return [
            ['quote' => 'Dalam 6 bulan belajar di 1Langkah, saya berhasil pindah dari non-tech ke Frontend Developer di Tokopedia. Kurikulum yang sangat terstruktur dan mentor yang luar biasa!', 'name' => 'Aisyah Putri', 'role' => 'Frontend Developer · Tokopedia', 'initials' => 'AP'],
            ['quote' => 'Kualitas kursus Data Science-nya setara dengan bootcamp luar negeri yang harganya 5x lipat. Saya sangat merekomendasikan platform ini untuk siapa saja yang ingin serius berkarir di tech.', 'name' => 'Dimas Prasetyo', 'role' => 'Data Scientist · Gojek', 'initials' => 'DP'],
            ['quote' => 'Mentor marketplace-nya luar biasa. Bisa sesi 1-on-1 dengan engineer dari Google dan Gojek benar-benar mempercepat growth saya sebagai developer.', 'name' => 'Nadya Ramadhani', 'role' => 'UI/UX Designer · Shopee', 'initials' => 'NR'],
        ];
    }

    public function calendarEvents(): array
    {
        return [
            ['date' => 3,  'title' => 'React Hooks Live Session',  'time' => '10:00 - 12:00'],
            ['date' => 7,  'title' => 'Assignment Due: UI Design', 'time' => '23:59'],
            ['date' => 10, 'title' => 'Mentor Session: Rudi',       'time' => '14:00 - 15:00'],
            ['date' => 14, 'title' => 'Quiz: Data Science Ch.3',    'time' => '09:00 - 10:00'],
            ['date' => 18, 'title' => 'Bootcamp: Full-Stack #4',    'time' => '10:00 - 13:00'],
            ['date' => 22, 'title' => 'Community Meetup',           'time' => '19:00 - 21:00'],
            ['date' => 25, 'title' => 'Certificate Ceremony',       'time' => '16:00 - 17:00'],
            ['date' => 28, 'title' => 'ML Workshop',                'time' => '10:00 - 12:00'],
        ];
    }

    public function categories(): array
    {
        return ['Semua', 'Programming', 'Data Science', 'Design', 'Marketing', 'Business', 'Cloud', 'Security'];
    }

    public function levels(): array
    {
        return ['Semua Level', 'Beginner', 'Intermediate', 'Advanced'];
    }

    public function mentorCategories(): array
    {
        return ['Semua', 'Programming', 'Design', 'Data Science', 'Marketing', 'Leadership', 'Cloud'];
    }

    public function skills(): array
    {
        return [
            ['name' => 'Frontend', 'pct' => 85, 'color' => 'var(--primary)'],
            ['name' => 'Backend',  'pct' => 70, 'color' => 'var(--blue)'],
            ['name' => 'Design',   'pct' => 45, 'color' => 'var(--purple)'],
            ['name' => 'Data',     'pct' => 55, 'color' => 'var(--success)'],
            ['name' => 'Cloud',    'pct' => 30, 'color' => 'var(--gold)'],
            ['name' => 'AI/ML',    'pct' => 40, 'color' => '#f5576c'],
        ];
    }

    public function weeklyHours(): array
    {
        return [
            ['day' => 'Mon', 'hours' => 2.5],
            ['day' => 'Tue', 'hours' => 3.1],
            ['day' => 'Wed', 'hours' => 2.8],
            ['day' => 'Thu', 'hours' => 4.2],
            ['day' => 'Fri', 'hours' => 3.5],
            ['day' => 'Sat', 'hours' => 1.2],
            ['day' => 'Sun', 'hours' => 1.0],
        ];
    }
}
