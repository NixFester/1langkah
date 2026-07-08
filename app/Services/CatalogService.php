<?php

namespace App\Services;

use App\Models\Bootcamp;
use App\Models\BootcampSession;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Mentor;
use App\Models\User;
use App\Models\UserActivityLog;
use Carbon\Carbon;

class CatalogService
{
    // ── Private mappers ──────────────────────────────────────────────────────
    private function mapCourse(Course $c): array
    {
        // Use actual enrollment count from database
        $enrolledCount = $c->enrollments()->count();

        // Decode JSON resources/benefits if stored as string
        $resources = [];
        if (! empty($c->resources)) {
            if (is_string($c->resources)) {
                $decoded = json_decode($c->resources, true);
                $resources = is_array($decoded) ? $decoded : [];
            } elseif (is_array($c->resources)) {
                $resources = $c->resources;
            }
        }

        $benefits = [];
        if (! empty($c->benefits)) {
            if (is_string($c->benefits)) {
                $decoded = json_decode($c->benefits, true);
                $benefits = is_array($decoded) ? $decoded : [];
            } elseif (is_array($c->benefits)) {
                $benefits = $c->benefits;
            }
        }

        $curriculum = [];
        if (! empty($c->curriculum)) {
            if (is_string($c->curriculum)) {
                $decoded = json_decode($c->curriculum, true);
                $curriculum = is_array($decoded) ? $decoded : [];
            } elseif (is_array($c->curriculum)) {
                $curriculum = $c->curriculum;
            }
        }

        return [
            'id' => $c->id,
            'title' => $c->title,
            'description' => $c->description ?? '',
            'short_description' => $c->short_description ?? '',
            'mentor' => $c->mentor_name ?? '',
            'mentorCompany' => $c->mentor_company ?? '',
            'category' => $c->category ?? '',
            'level' => $c->level ?? 'Beginner',
            'badge' => $c->badge ?? '',
            'rating' => (float) ($c->rating ?? 0),
            'students' => $enrolledCount,
            'enrolled_count' => $enrolledCount,
            'enrolledCount' => $enrolledCount,
            'price' => $c->price ?? '',
            'formatted_price' => $c->formatted_price ?? '',
            'progress' => $c->progress ?? 0,
            'color' => $c->color ?? '#dc2626',
            'thumbnail' => $c->pictures?->where('type', 'thumbnail')->first()?->url,
            'gallery' => $c->pictures?->where('type', 'array')->sortBy('order')->pluck('url')->values()->toArray() ?? [],
            'resources' => $resources,
            'benefits' => $benefits,
            'curriculum' => $curriculum,
        ];
    }

    private function mapOnlineBootcamp(Bootcamp $b): array
    {
        // Ensure pictures relationship returns a collection
        $pictures = $b->pictures ?? collect();

        // Calculate enrolled count
        $enrolledCount = $b->enrollments()->count();
        $totalSlots = $b->participants ?? 40;
        $availableSlots = max(0, $totalSlots - $enrolledCount);

        return [
            'id' => $b->id,
            'title' => $b->title,
            'mentor' => $b->mentor_name,
            'participants' => $b->participants,
            'startDate' => $b->start_date,
            'sessions' => $b->sessions_info,
            'price' => $b->price,
            'formatted_price' => $b->formatted_price ?? '',
            'color' => $b->color,
            'thumbnail' => $pictures->where('type', 'thumbnail')->first()?->url,
            'gallery' => $pictures->where('type', 'array')->sortBy('order')->pluck('url')->values()->toArray(),
            'enrolledCount' => $enrolledCount,
            'availableSlots' => $availableSlots,
            'totalSlots' => $totalSlots,
        ];
    }

    private function mapOfflineBootcamp(Bootcamp $b): array
    {
        // Safely get attributes that might not exist in older migrations
        $attrs = $b->getAttributes();
        // Use property access ($b->benefits) so Laravel's 'array' cast is applied,
        // rather than getAttributes() which returns raw database values
        $benefits = $b->benefits;
        if (empty($benefits)) {
            $benefits = $this->offlineFeatures();
        }

        $jadwalKelas = [];
        if (isset($attrs['jadwal_kelas'])) {
            $jadwalKelas = is_string($attrs['jadwal_kelas']) ? json_decode($attrs['jadwal_kelas'], true) : $attrs['jadwal_kelas'];
        }

        // Ensure pictures relationship returns a collection
        $pictures = $b->pictures ?? collect();

        // Calculate enrolled count
        $enrolledCount = $b->enrollments()->count();
        $totalSlots = $b->participants ?? 20;
        $availableSlots = max(0, $totalSlots - $enrolledCount);

        return [
            'id' => $b->id,
            'title' => $b->title,
            'mentor' => $b->mentor_name,
            'participants' => $b->participants,
            'startDate' => $b->start_date,
            'location' => $b->location,
            'price' => $b->price,
            'formatted_price' => $b->formatted_price ?? '',
            'color' => $b->color,
            'icon' => $attrs['icon'] ?? 'graduation-cap',
            'benefits' => $benefits,
            'jadwal_kelas' => $jadwalKelas,
            'thumbnail' => $pictures->where('type', 'thumbnail')->first()?->url,
            'gallery' => $pictures->where('type', 'array')->sortBy('order')->pluck('url')->values()->toArray(),
            'enrolledCount' => $enrolledCount,
            'availableSlots' => $availableSlots,
            'totalSlots' => $totalSlots,
        ];
    }

    private function mapMentor(Mentor $m): array
    {
        return [
            'id' => $m->id,
            'name' => $m->name,
            'role' => $m->role,
            'company' => $m->company,
            'price' => $m->price,
            'formatted_price' => $m->formatted_price ?? '',
            'rating' => (float) $m->rating,
            'sessions' => $m->sessions_count,
            'initials' => $m->initials,
            'color' => $m->color,
            'expertise' => $m->expertise ?? [],
            'bio' => $m->bio,
            'linkedin_url' => $m->linkedin_url,
            'phone' => $m->phone,
            'wa_link' => $m->wa_link,
        ];
    }

    private function mapEvent(Event $e): array
    {
        $startDt = $e->start_date instanceof \Illuminate\Support\Carbon ? $e->start_date : Carbon::parse($e->start_date);
        $endDt = $e->end_date ? ($e->end_date instanceof \Illuminate\Support\Carbon ? $e->end_date : Carbon::parse($e->end_date)) : null;

        return [
            'id' => $e->id,
            'title' => $e->title,
            'slug' => $e->slug,
            'description' => $e->description,
            'short_description' => $e->short_description ?? '',
            'start_date' => $e->start_date,
            'end_date' => $e->end_date,
            'timezone' => $e->timezone ?? 'Asia/Jakarta',
            'type' => $e->type,
            'location' => $e->location,
            'meeting_url' => $e->meeting_url,
            'status' => $e->status,
            'max_participants' => $e->max_participants,
            'registered_count' => $e->registered_count ?? 0,
            'color' => $e->color ?? '#cc0000',
            'banner_url' => $e->banner_url,
            'start_day' => $startDt->day,
            'start_month' => $startDt->month,
            'start_year' => $startDt->year,
            'start_time' => $startDt->format('H:i').' WIB',
            'end_time' => $endDt ? $endDt->format('H:i').' WIB' : null,
            'date_display' => $startDt->format('d M Y'),
            'day_name' => $startDt->dayName,
        ];
    }

    // ── Public API ───────────────────────────────────────────────────────────

    public function user(): array
    {
        $user = auth()->user();
        if (! $user) {
            return ['name' => 'Guest', 'initials' => 'G', 'role' => 'Visitor', 'xp' => '0 XP', 'streak' => 0, 'careerReady' => 0];
        }

        $initials = implode('', array_map(fn ($w) => $w[0] ?? '', explode(' ', $user->name)));

        return [
            'name' => $user->name,
            'initials' => strtoupper(substr($initials, 0, 2)),
            'role' => ucfirst($user->role),
            'xp' => '1,240 XP', // Static for now, unless you add an XP column
            'streak' => 12,         // Static for now
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
                'title' => $ch->title,
                'lessons' => $ch->lessons,
                'duration' => $ch->duration,
                'video_url' => $ch->video_url,
                'thumbnail_url' => $ch->thumbnail_url,
                'description' => $ch->description,
            ])
            ->toArray();
    }

    public function bootcamps(): array
    {
        return [
            'online' => Bootcamp::with('pictures')->where('type', 'online')->get()
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
                'id' => $s->id,
                'date' => $s->date,
                'topic' => $s->topic,
                'time' => $s->time,
                'meeting_url' => $s->meeting_url,
                'description' => $s->description,
                'password' => $s->password,
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
        // Fetch mentors from Mentor model where user_id is not null (properly linked)
        return Mentor::whereNotNull('user_id')
            ->whereHas('user', fn ($q) => $q->where('role', 'mentor'))
            ->with('user')
            ->get()
            ->map(function ($mentor) {
                return $this->mapMentorData($mentor);
            })
            ->values()
            ->toArray();
    }

    public function mentor(int $id): ?array
    {
        // Find mentor by user_id (not Mentor.id)
        $mentor = Mentor::where('user_id', $id)
            ->with(['user', 'schedules', 'courses.ratings', 'bootcamps.ratings'])
            ->first();

        if (! $mentor) {
            return null;
        }

        return $this->mapMentorData($mentor);
    }

    /**
     * Map Mentor model to array with all related data
     */
    private function mapMentorData(Mentor $mentor): array
    {
        // Calculate real rating from course and bootcamp ratings
        $courseRatings = $mentor->courses->flatMap->ratings;
        $bootcampRatings = $mentor->bootcamps->flatMap->ratings;
        $allRatings = $courseRatings->concat($bootcampRatings);
        $avgRating = $allRatings->count() > 0 ? $allRatings->avg('rating') : ($mentor->rating ?? 0);

        // Get schedules for available days
        $schedules = $mentor->schedules->map(function ($schedule) {
            $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

            return [
                'day' => $schedule->day_of_week,
                'day_name' => $dayNames[$schedule->day_of_week] ?? 'Unknown',
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'is_available' => $schedule->is_available,
            ];
        })->toArray();

        // Check if available today
        $today = strtolower(now()->format('l'));
        $dayMap = ['sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6];
        $todayDayNum = $dayMap[$today] ?? 0;
        $isTodayAvailable = collect($schedules)->contains(fn ($s) => $s['day'] === $todayDayNum && $s['is_available']);

        return [
            'id' => $mentor->user_id,
            'name' => $mentor->name,
            'role' => $mentor->role,
            'company' => $mentor->company,
            'price' => $mentor->price,
            'formatted_price' => $this->formatPrice($mentor->price),
            'rating' => (float) $avgRating,
            'sessions' => $mentor->sessions_count ?? 0,
            'rating_count' => $allRatings->count(),
            'initials' => $mentor->initials,
            'color' => $mentor->color ?? '#dc2626',
            'expertise' => $mentor->expertise ?? [],
            'bio' => $mentor->bio,
            'linkedin_url' => $mentor->linkedin_url,
            'phone' => $mentor->phone,
            'wa_link' => $this->generateWaLink($mentor->phone),
            'profile_photo' => $mentor->user?->profile_photo,
            'schedules' => $schedules,
            'is_today_available' => $isTodayAvailable,
        ];
    }

    private function formatPrice(string|int $price): string
    {
        $priceRaw = trim(str_replace('/sesi', '', (string) $price));
        if (empty($priceRaw) || $priceRaw == '0' || strtolower($priceRaw) === 'gratis') {
            return 'Gratis';
        }
        if (is_numeric($priceRaw)) {
            return 'Rp '.number_format((float) $priceRaw, 0, ',', '.');
        }

        return (string) $price;
    }

    private function generateWaLink(?string $phone): string
    {
        if (empty($phone)) {
            return 'https://wa.me/08123456789';
        }
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        return 'https://wa.me/'.$cleanPhone;
    }

    // ── User Enrollment Methods ───────────────────────────────────────────────

    /**
     * Get user's enrolled courses with progress
     */
    public function userEnrolledCourses(int $userId): array
    {
        $user = User::with([
            'enrollments.purchasable.pictures',
            'chapterProgress',
        ])->find($userId);

        if (! $user) {
            return [];
        }

        $enrollments = $user->enrollments->filter(function ($e) {
            return $e->purchasable_type === Course::class && $e->purchasable;
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
            ->where('completable_type', Course::class)
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
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description ?? '',
                'short_description' => $course->short_description ?? '',
                'mentor' => $course->mentor_name ?? '',
                'mentorCompany' => $course->mentor_company ?? '',
                'category' => $course->category ?? '',
                'level' => $course->level ?? 'Beginner',
                'badge' => $course->badge ?? '',
                'rating' => (float) ($course->rating ?? 0),
                'students' => max((int) ($course->students_count ?? 0), $course->enrollments()->count()),
                'enrolled_count' => max((int) ($course->students_count ?? 0), $course->enrollments()->count()),
                'enrolledCount' => max((int) ($course->students_count ?? 0), $course->enrollments()->count()),
                'price' => $course->price ?? '',
                'color' => $course->color ?? '#dc2626',
                'progress' => $isCompleted ? 100 : $progress,
                'is_completed' => $isCompleted,
                'completed' => $completedChapters,
                'total' => $totalChapters,
                'thumbnail' => $course->pictures?->where('type', 'thumbnail')->first()?->url,
                'enrolled_at' => $enrollment->created_at,
            ];
        })->values()->toArray();
    }

    /**
     * Get user's enrolled bootcamps with progress
     */
    public function userEnrolledBootcamps(int $userId): array
    {
        $user = User::with([
            'enrollments.purchasable.pictures',
            'sessionProgress',
        ])->find($userId);

        if (! $user) {
            return [];
        }

        $enrollments = $user->enrollments->filter(function ($e) {
            return $e->purchasable_type === Bootcamp::class && $e->purchasable;
        });

        return $enrollments->map(function ($enrollment) use ($user) {
            $bootcamp = $enrollment->purchasable;
            $pictures = $bootcamp->pictures ?? collect();
            $sessions = $bootcamp->sessions ?? collect();
            $totalSessions = $sessions->count();
            $clickedSessions = $user->sessionProgress
                ->whereIn('bootcamp_session_id', $sessions->pluck('id'))
                ->filter(fn ($p) => $p->clicked_at !== null)
                ->count();

            $progress = $totalSessions > 0 ? round(($clickedSessions / $totalSessions) * 100) : 0;

            return [
                'id' => $bootcamp->id,
                'title' => $bootcamp->title,
                'mentor' => $bootcamp->mentor_name,
                'type' => $bootcamp->type,
                'rating' => (float) $bootcamp->rating,
                'progress' => $progress,
                'sessions' => $totalSessions,
                'attended' => $clickedSessions,
                'thumbnail' => $pictures->where('type', 'thumbnail')->first()?->url,
                'enrolled_at' => $enrollment->created_at,
            ];
        })->values()->toArray();
    }

    /**
     * Get user's dashboard stats (simplified, no gamification)
     */
    public function userStats(int $userId): array
    {
        $user = User::with([
            'enrollments',
            'completions',
        ])->find($userId);

        if (! $user) {
            return [
                'courses_enrolled' => 0,
                'bootcamps_enrolled' => 0,
                'courses_completed' => 0,
                'bootcamps_completed' => 0,
                'certificates' => 0,
            ];
        }

        $courseEnrollments = $user->enrollments->where('purchasable_type', Course::class)->count();
        $bootcampEnrollments = $user->enrollments->where('purchasable_type', Bootcamp::class)->count();
        $coursesCompleted = $user->completions->where('completable_type', Course::class)->count();
        $bootcampsCompleted = $user->completions->where('completable_type', Bootcamp::class)->count();

        return [
            'courses_enrolled' => $courseEnrollments,
            'bootcamps_enrolled' => $bootcampEnrollments,
            'courses_completed' => $coursesCompleted,
            'bootcamps_completed' => $bootcampsCompleted,
            'certificates' => $coursesCompleted + $bootcampsCompleted,
        ];
    }

    /**
     * Get recent activities for the user
     */
    public function recentActivities(int $userId): array
    {
        $logs = UserActivityLog::with('loggable')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        if ($logs->isEmpty()) {
            return [];
        }

        return $logs->map(function ($log) {
            $loggableName = $log->loggable ? ($log->loggable->title ?? 'an item') : 'the platform';
            $actionText = match ($log->action) {
                'enrolled' => 'Enrolled in',
                'completed' => 'Completed',
                'started' => 'Started',
                default => ucfirst($log->action),
            };

            return [
                'text' => $actionText.' '.$loggableName,
                'time' => $log->created_at->diffForHumans(),
                'color' => '#3b82f6',
            ];
        })->toArray();
    }

    /**
     * Get upcoming events for the calendar widget
     */
    public function upcomingEvents(?int $userId = null, int $limit = 3): array
    {
        $query = Event::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit($limit);

        return $query->get()->map(function ($e) {
            $dt = $e->start_date instanceof \Illuminate\Support\Carbon ? $e->start_date : Carbon::parse($e->start_date);

            return [
                'id' => $e->id,
                'title' => $e->title,
                'date' => $dt->format('d M'),
                'day' => $dt->dayName,
                'time' => $dt->format('H:i').' WIB',
                'type' => $e->type,
                'color' => $e->color ?? '#cc0000',
                'banner_url' => $e->banner_url,
            ];
        })->toArray();
    }

    /**
     * Get recommended courses based on user's enrolled categories
     */
    public function recommendedCourses(int $userId, int $limit = 3): array
    {
        // Get user's enrolled course categories
        $enrolledCourseIds = Enrollment::where('user_id', $userId)
            ->where('purchasable_type', Course::class)
            ->pluck('purchasable_id');

        $enrolledCategories = Course::whereIn('id', $enrolledCourseIds)
            ->pluck('category')
            ->filter()
            ->unique()
            ->toArray();

        // Get courses not yet enrolled
        $excludeIds = Enrollment::where('user_id', $userId)
            ->where('purchasable_type', Course::class)
            ->pluck('purchasable_id')
            ->toArray();

        $query = Course::with('pictures')
            ->whereNotIn('id', $excludeIds)
            ->orderByDesc('rating')
            ->limit($limit);

        // If user has enrolled courses, prioritize same category
        if (! empty($enrolledCategories)) {
            $query->orderByRaw("CASE WHEN category IN ('".implode("','", $enrolledCategories)."') THEN 0 ELSE 1 END");
        }

        return $query->get()
            ->map(fn ($c) => $this->mapCourse($c))
            ->values()
            ->toArray();
    }

    public function testimonials(): array
    {
        return [
            ['quote' => 'Dalam 6 bulan belajar di 1Langkah...', 'name' => 'Aisyah Putri',    'role' => 'Frontend Developer · Tokopedia', 'initials' => 'AP'],
            ['quote' => 'Kualitas kursus Data Science-nya...',   'name' => 'Dimas Prasetyo',  'role' => 'Data Scientist · Gojek',        'initials' => 'DP'],
            ['quote' => 'Mentor marketplace-nya luar biasa...',  'name' => 'Nadya Ramadhani', 'role' => 'UI/UX Designer · Shopee',       'initials' => 'NR'],
        ];
    }

    public function calendarEvents($year = null, $month = null): array
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $events = Event::where('start_date', '>=', $startOfMonth)
            ->where('start_date', '<=', $endOfMonth)
            ->orderBy('start_date')
            ->get();

        $bootcamps = Bootcamp::where('start_date', '>=', $startOfMonth->toDateTimeString())
            ->where('start_date', '<=', $endOfMonth->toDateTimeString())
            ->orderBy('start_date')
            ->get();

        $mappedEvents = $events->map(function ($e) {
            $dt = $e->start_date instanceof \Illuminate\Support\Carbon ? $e->start_date : Carbon::parse($e->start_date);

            return [
                'id' => $e->id,
                'day' => $dt->day,
                'title' => $e->title,
                'time' => $dt->format('H:i').' WIB',
                'type' => $e->type,
                'color' => $e->color ?? '#cc0000',
                'source' => 'event',
                'url' => route('detail-event', $e->id),
            ];
        })->values();

        $mappedBootcamps = $bootcamps->map(function ($b) {
            $dt = Carbon::parse($b->start_date);

            return [
                'id' => $b->id,
                'day' => $dt->day,
                'title' => $b->title,
                'time' => $dt->format('H:i').' WIB',
                'type' => 'bootcamp',
                'color' => $b->color ?? '#cc0000',
                'source' => 'bootcamp',
                'url' => $b->type === 'online' ? route('detail-online-bootcamp', $b->id) : route('detail-offline-bootcamp', $b->id),
            ];
        })->values();

        return $mappedEvents->concat($mappedBootcamps)->all();
    }

    public function userRegisteredEvents(int $userId): array
    {
        return EventRegistration::where('user_id', $userId)
            ->pluck('event_id')
            ->toArray();
    }

    public function events(): array
    {
        return Event::with('registeredUsers')
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(fn ($e) => $this->mapEvent($e))
            ->values()
            ->toArray();
    }

    public function event(int $id): ?array
    {
        $e = Event::find($id);

        return $e ? $this->mapEvent($e) : null;
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
}
