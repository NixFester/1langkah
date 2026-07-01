<?php

namespace App\Services;

use App\Models\Bootcamp;
use App\Models\BootcampSession;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Mentor;

class CatalogService
{
    // ── Private mappers ──────────────────────────────────────────────────────
    private function mapCourse(Course $c): array
    {
        return [
            'id'            => $c->id,
            'title'         => $c->title,
            'description'   => $c->description ?? '',
            'short_description' => $c->short_description ?? '',
            'mentor'        => $c->mentor_name ?? '',
            'mentorCompany' => $c->mentor_company ?? '',
            'category'      => $c->category ?? '',
            'level'         => $c->level ?? 'Beginner',
            'badge'         => $c->badge ?? '',
            'rating'        => (float) ($c->rating ?? 0),
            'students'      => $c->students_count ?? 0,
            'price'         => $c->price ?? '',
            'progress'      => $c->progress ?? 0,
            'color'         => $c->color ?? '#dc2626',
            'thumbnail'     => $c->pictures?->where('type', 'thumbnail')->first()?->url,
            'gallery'       => $c->pictures?->where('type', 'gallery')->sortBy('order')->pluck('url')->values()->toArray() ?? [],
        ];
    }

    private function mapOnlineBootcamp(Bootcamp $b): array
    {
        // Ensure pictures relationship returns a collection
        $pictures = $b->pictures ?? collect();

        return [
            'id'           => $b->id,
            'title'        => $b->title,
            'mentor'       => $b->mentor_name,
            'participants' => $b->participants,
            'startDate'    => $b->start_date,
            'sessions'     => $b->sessions_info,
            'price'        => $b->price,
            'color'        => $b->color,
            'thumbnail' => $pictures->where('type', 'thumbnail')->first()?->url,
            'gallery'   => $pictures->where('type', 'gallery')->sortBy('order')->pluck('url')->values()->toArray(),
        ];
    }

    private function mapOfflineBootcamp(Bootcamp $b): array
    {
        // Safely get attributes that might not exist in older migrations
        $attrs = $b->getAttributes();
        $benefits = [];
        if (isset($attrs['benefits'])) {
            if (is_array($attrs['benefits'])) {
                $benefits = $attrs['benefits'];
            } elseif (is_string($attrs['benefits']) && !empty(trim($attrs['benefits']))) {
                $decoded = json_decode($attrs['benefits'], true);
                $benefits = is_array($decoded) ? $decoded : [$attrs['benefits']];
            }
        }
        if (empty($benefits)) {
            $benefits = $this->offlineFeatures();
        }

        $jadwalKelas = [];
        if (isset($attrs['jadwal_kelas'])) {
            $jadwalKelas = is_string($attrs['jadwal_kelas']) ? json_decode($attrs['jadwal_kelas'], true) : $attrs['jadwal_kelas'];
        }

        // Ensure pictures relationship returns a collection
        $pictures = $b->pictures ?? collect();

        return [
            'id'           => $b->id,
            'title'        => $b->title,
            'mentor'       => $b->mentor_name,
            'participants' => $b->participants,
            'startDate'    => $b->start_date,
            'location'     => $b->location,
            'price'        => $b->price,
            'color'        => $b->color,
            'icon'         => $attrs['icon'] ?? 'graduation-cap',
            'benefits'     => $benefits,
            'jadwal_kelas' => $jadwalKelas,
            'thumbnail' => $pictures->where('type', 'thumbnail')->first()?->url,
            'gallery'   => $pictures->where('type', 'gallery')->sortBy('order')->pluck('url')->values()->toArray(),
        ];
    }

    private function mapMentor(Mentor $m): array
    {
        return [
            'id'        => $m->id,
            'name'      => $m->name,
            'role'      => $m->role,
            'company'   => $m->company,
            'price'     => $m->price,
            'rating'    => (float) $m->rating,
            'sessions'  => $m->sessions_count,
            'initials'  => $m->initials,
            'color'     => $m->color,
            'expertise' => $m->expertise ?? [],
            'bio'       => $m->bio,
            'linkedin_url' => $m->linkedin_url,
        ];
    }

    // ── Public API ───────────────────────────────────────────────────────────

    public function user(): array
    {
        $user = auth()->user();
        if (!$user) {
            return ['name' => 'Guest', 'initials' => 'G', 'role' => 'Visitor', 'xp' => '0 XP', 'streak' => 0, 'careerReady' => 0];
        }
        
        $initials = implode('', array_map(fn($w) => $w[0] ?? '', explode(' ', $user->name)));
        
        return [
            'name'        => $user->name,
            'initials'    => strtoupper(substr($initials, 0, 2)),
            'role'        => ucfirst($user->role),
            'xp'          => '1,240 XP', // Static for now, unless you add an XP column
            'streak'      => 12,         // Static for now
            'careerReady' => 76,         // Static for now
        ];
    }

    public function courses(): array
    {
        return Course::with('pictures')->get()
            ->map(fn ($c) => $this->mapCourse($c))->values()->toArray();
    }

    public function course(int $id): ?array
    {
        $c = Course::with('pictures')->find($id);
        return $c ? $this->mapCourse($c) : null;
    }

    /** Returns chapters belonging to a specific course. */
    public function chapters(int $courseId): array
    {
        return Chapter::where('course_id', $courseId)
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->map(fn ($ch) => [
                'title'        => $ch->title,
                'lessons'      => $ch->lessons,
                'duration'     => $ch->duration,
                'video_url'    => $ch->video_url,
                'thumbnail_url'=> $ch->thumbnail_url,
                'description'  => $ch->description,
            ])
            ->toArray();
    }

    public function bootcamps(): array
    {
        return [
            'online'  => Bootcamp::with('pictures')->where('type', 'online')->get()
                ->map(fn ($b) => $this->mapOnlineBootcamp($b))->values()->toArray(),
            'offline' => Bootcamp::with('pictures')->where('type', 'offline')->get()
                ->map(fn ($b) => $this->mapOfflineBootcamp($b))->values()->toArray(),
        ];
    }

    public function onlineBootcamp(int $id): ?array
    {
        $b = Bootcamp::with('pictures')->where('type', 'online')->find($id);
        return $b ? $this->mapOnlineBootcamp($b) : null;
    }

    public function offlineBootcamp(int $id): ?array
    {
        $b = Bootcamp::with('pictures')->where('type', 'offline')->find($id);
        return $b ? $this->mapOfflineBootcamp($b) : null;
    }

    /** Returns sessions for a specific bootcamp. */
    public function onlineSessions(int $bootcampId): array
    {
        return BootcampSession::where('bootcamp_id', $bootcampId)
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->map(fn ($s) => [
                'date'        => $s->date,
                'topic'       => $s->topic,
                'time'        => $s->time,
                'meeting_url' => $s->meeting_url,
                'description' => $s->description,
                'password'    => $s->password,
            ])
            ->toArray();
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
        return Mentor::all()->map(fn ($m) => $this->mapMentor($m))->values()->toArray();
    }

    public function mentor(int $id): ?array
    {
        $m = Mentor::find($id);
        return $m ? $this->mapMentor($m) : null;
    }

    // ── User Enrollment Methods ───────────────────────────────────────────────

    /**
     * Get user's enrolled courses with progress
     */
    public function userEnrolledCourses(int $userId): array
    {
        $user = \App\Models\User::with([
            'enrollments.purchasable.pictures',
            'chapterProgress'
        ])->find($userId);

        if (!$user) {
            return [];
        }

        $enrollments = $user->enrollments->filter(function ($e) {
            return $e->purchasable_type === \App\Models\Course::class && $e->purchasable;
        });

        if ($enrollments->isEmpty()) {
            return [];
        }

        $courseIds = $enrollments->pluck('purchasable_id')->filter();
        $courses = Course::whereIn('id', $courseIds)
            ->with('chapters', 'pictures')
            ->get()
            ->keyBy('id');

        // Pre-load completions for this user
        $completionRecords = $user->completions
            ->where('completable_type', \App\Models\Course::class)
            ->keyBy('completable_id');

        return $enrollments->map(function ($enrollment) use ($user, $courses, $completionRecords) {
            $course = $courses->get($enrollment->purchasable_id) ?? $enrollment->purchasable;
            $chapters = $course->chapters ?? collect();
            $totalChapters = $chapters->count();
            $completedChapters = $user->chapterProgress
                ->whereIn('chapter_id', $chapters->pluck('id'))
                ->where('is_completed', true)
                ->count();

            $progress = $totalChapters > 0 ? round(($completedChapters / $totalChapters) * 100) : 0;

            // Also consider explicit completions table record
            $isCompleted = $completionRecords->has($course->id);

            return [
                'id'            => $course->id,
                'title'         => $course->title,
                'description'   => $course->description ?? '',
                'short_description' => $course->short_description ?? '',
                'mentor'        => $course->mentor_name ?? '',
                'mentorCompany' => $course->mentor_company ?? '',
                'category'      => $course->category ?? '',
                'level'         => $course->level ?? 'Beginner',
                'badge'         => $course->badge ?? '',
                'rating'        => (float) ($course->rating ?? 0),
                'students'      => $course->students_count ?? 0,
                'price'         => $course->price ?? '',
                'color'         => $course->color ?? '#dc2626',
                'progress'      => $isCompleted ? 100 : $progress,
                'is_completed'  => $isCompleted,
                'completed'     => $completedChapters,
                'total'         => $totalChapters,
                'thumbnail'     => $course->pictures?->where('type', 'thumbnail')->first()?->url,
                'enrolled_at'   => $enrollment->created_at,
            ];
        })->values()->toArray();
    }

    /**
     * Get user's enrolled bootcamps with progress
     */
    public function userEnrolledBootcamps(int $userId): array
    {
        $user = \App\Models\User::with([
            'enrollments.purchasable.pictures',
            'sessionProgress'
        ])->find($userId);

        if (!$user) {
            return [];
        }

        $enrollments = $user->enrollments->filter(function ($e) {
            return $e->purchasable_type === \App\Models\Bootcamp::class && $e->purchasable;
        });

        return $enrollments->map(function ($enrollment) use ($user) {
            $bootcamp = $enrollment->purchasable;
            $pictures = $bootcamp->pictures ?? collect();
            $sessions = $bootcamp->sessions ?? collect();
            $totalSessions = $sessions->count();
            $clickedSessions = $user->sessionProgress
                ->whereIn('bootcamp_session_id', $sessions->pluck('id'))
                ->filter(fn($p) => $p->clicked_at !== null)
                ->count();

            $progress = $totalSessions > 0 ? round(($clickedSessions / $totalSessions) * 100) : 0;

            return [
                'id'            => $bootcamp->id,
                'title'         => $bootcamp->title,
                'mentor'        => $bootcamp->mentor_name,
                'type'          => $bootcamp->type,
                'rating'        => (float) $bootcamp->rating,
                'progress'      => $progress,
                'sessions'       => $totalSessions,
                'attended'      => $clickedSessions,
                'thumbnail'     => $pictures->where('type', 'thumbnail')->first()?->url,
                'enrolled_at'   => $enrollment->created_at,
            ];
        })->values()->toArray();
    }

    /**
     * Get user's dashboard stats
     */
    public function userStats(int $userId): array
    {
        $user = \App\Models\User::with([
            'enrollments',
            'completions',
            'chapterProgress',
            'courseRatings',
            'bootcampRatings',
            'skills'
        ])->find($userId);

        if (!$user) {
            return [
                'courses_enrolled' => 0,
                'bootcamps_enrolled' => 0,
                'courses_completed' => 0,
                'bootcamps_completed' => 0,
                'certificates' => 0,
                'xp' => 0,
                'streak' => 0,
                'skills_count' => 0,
            ];
        }

        $courseEnrollments = $user->enrollments->where('purchasable_type', \App\Models\Course::class)->count();
        $bootcampEnrollments = $user->enrollments->where('purchasable_type', \App\Models\Bootcamp::class)->count();
        $coursesCompleted = $user->completions->where('completable_type', \App\Models\Course::class)->count();
        $bootcampsCompleted = $user->completions->where('completable_type', \App\Models\Bootcamp::class)->count();

        // Calculate XP
        $xp = $user->completions->count() * 100
            + $user->chapterProgress->count() * 10
            + $user->courseRatings->count() * 50
            + $user->bootcampRatings->count() * 50;

        return [
            'courses_enrolled' => $courseEnrollments,
            'bootcamps_enrolled' => $bootcampEnrollments,
            'courses_completed' => $coursesCompleted,
            'bootcamps_completed' => $bootcampsCompleted,
            'certificates' => $coursesCompleted + $bootcampsCompleted,
            'xp' => $xp,
            'streak' => $user->streak,
            'skills_count' => $user->skills->pluck('skill_name')->filter()->unique()->count(),
        ];
    }

    // ── Still hardcoded (no DB table yet) ────────────────────────────────────

    public function leaderboard(): array
    {
        return [
            ['name' => 'Ahmad Fauzi',  'xp' => '12,480 XP', 'rank' => 1, 'initials' => 'AF'],
            ['name' => 'Siti Rahma',   'xp' => '11,920 XP', 'rank' => 2, 'initials' => 'SR'],
            ['name' => 'Dito Pratama', 'xp' => '10,750 XP', 'rank' => 3, 'initials' => 'DP'],
            ['name' => 'You (Reza)',   'xp' => '9,840 XP',  'rank' => 4, 'initials' => 'AK', 'isMe' => true],
            ['name' => 'Maya Sari',    'xp' => '8,920 XP',  'rank' => 5, 'initials' => 'MS'],
        ];
    }

    public function activities(): array
    {
        $logs = \App\Models\UserActivityLog::with('loggable')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        if ($logs->isEmpty()) {
            return [['text' => 'No recent activity', 'time' => 'Just now', 'color' => '#6b7280']];
        }

        return $logs->map(function ($log) {
            $loggableName = $log->loggable ? ($log->loggable->title ?? 'a course') : 'the platform';
            return [
                'text'  => ucfirst($log->action) . ' ' . $loggableName,
                'time'  => $log->created_at->diffForHumans(),
                'color' => '#3b82f6'
            ];
        })->toArray();
    }

    public function testimonials(): array
    {
        return [
            ['quote' => 'Dalam 6 bulan belajar di 1Langkah...', 'name' => 'Aisyah Putri',    'role' => 'Frontend Developer · Tokopedia', 'initials' => 'AP'],
            ['quote' => 'Kualitas kursus Data Science-nya...',   'name' => 'Dimas Prasetyo',  'role' => 'Data Scientist · Gojek',        'initials' => 'DP'],
            ['quote' => 'Mentor marketplace-nya luar biasa...',  'name' => 'Nadya Ramadhani', 'role' => 'UI/UX Designer · Shopee',       'initials' => 'NR'],
        ];
    }

    public function calendarEvents(): array
    {
        $events = \App\Models\Event::where('start_date', '>=', now()->startOfMonth())
            ->where('start_date', '<=', now()->endOfMonth())
            ->orderBy('start_date')
            ->get();

        $bootcamps = Bootcamp::where('start_date', '>=', now()->startOfMonth()->toDateTimeString())
            ->where('start_date', '<=', now()->endOfMonth()->toDateTimeString())
            ->orderBy('start_date')
            ->get();

        $mappedEvents = $events->map(function ($e) {
            $dt = $e->start_date instanceof \Illuminate\Support\Carbon ? $e->start_date : \Carbon\Carbon::parse($e->start_date);
            return [
                'day'   => $dt->day,
                'title' => $e->title,
                'time'  => $dt->format('H:i') . ' WIB',
                'type'  => $e->type,
            ];
        })->values();

        $mappedBootcamps = $bootcamps->map(function ($b) {
            $dt = \Carbon\Carbon::parse($b->start_date);
            return [
                'day'   => $dt->day,
                'title' => $b->title,
                'time'  => $dt->format('H:i') . ' WIB',
                'type'  => 'bootcamp',
            ];
        })->values();

        return $mappedEvents->concat($mappedBootcamps)->all();
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