<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'profile_photo',
        'bio',
        'xp',
        'level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'xp' => 'integer',
            'level' => 'integer',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function activityLogs()
    {
        return $this->hasMany(UserActivityLog::class);
    }

    public function chapterProgress()
    {
        return $this->hasMany(ChapterProgress::class);
    }

    public function testAttempts()
    {
        return $this->hasMany(TestAttempt::class);
    }

    public function completions()
    {
        return $this->hasMany(Completion::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function courseRatings()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function bootcampRatings()
    {
        return $this->hasMany(BootcampRating::class);
    }

    public function sessionProgress()
    {
        return $this->hasMany(SessionProgress::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function achievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function earnedAchievements()
    {
        return $this->hasMany(UserAchievement::class)->with('achievement');
    }

    public function xpTransactions()
    {
        return $this->hasMany(UserXpTransaction::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function mentor()
    {
        return $this->hasOne(Mentor::class);
    }

    // ── Role Helpers ────────────────────────────────────────────────────────────

    /**
     * Role hierarchy levels
     */
    public const ROLE_LEVELS = [
        'superadmin' => 6,
        'admin' => 5,
        'keuangan' => 4,
        'marketing' => 3,
        'mentor' => 2,
        'student' => 1,
    ];

    /**
     * Roles that can access admin panel
     */
    public const ADMIN_ROLES = ['superadmin', 'admin', 'keuangan', 'marketing'];

    /**
     * Role display labels
     */
    public const ROLE_LABELS = [
        'superadmin' => 'Super Admin',
        'admin' => 'Admin',
        'keuangan' => 'Keuangan',
        'marketing' => 'Marketing',
        'mentor' => 'Mentor',
        'student' => 'Student',
    ];

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user has role level >= required level
     */
    public function hasRoleLevel(string $requiredRole): bool
    {
        $userLevel = self::ROLE_LEVELS[$this->role] ?? 0;
        $requiredLevel = self::ROLE_LEVELS[$requiredRole] ?? 0;

        return $userLevel >= $requiredLevel;
    }

    /**
     * Check if user can access admin panel
     */
    public function canAccessAdmin(): bool
    {
        return in_array($this->role, self::ADMIN_ROLES);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is admin (any level)
     */
    public function isAdmin(): bool
    {
        return $this->canAccessAdmin();
    }

    /**
     * Check if user is mentor
     */
    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is keuangan
     */
    public function isKeuangan(): bool
    {
        return $this->role === 'keuangan';
    }

    /**
     * Check if user is marketing
     */
    public function isMarketing(): bool
    {
        return $this->role === 'marketing';
    }

    /**
     * Get role display label
     */
    public function getRoleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Get the dashboard route based on user role
     * Matches the role-flow-diagrams.md redirect logic
     */
    public function getDashboardRoute(): string
    {
        return match ($this->role) {
            'superadmin' => route('superadmin.dashboard'),
            'admin' => route('admin.dashboard'),
            'keuangan' => route('keuangan.dashboard'),
            'marketing' => route('marketing.dashboard'),
            'mentor' => route('mentor.dashboard'),
            'student' => route('dashboard'),
            default => route('dashboard'),
        };
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function hasCompletedCourse(Course $course): bool
    {
        return $this->completions()
            ->where('completable_type', Course::class)
            ->where('completable_id', $course->id)
            ->exists();
    }

    public function getCourseRatingAttribute(): ?int
    {
        return $this->courseRatings->first()?->rating;
    }

    public function getXpAttribute(): int
    {
        // Use stored XP value (default 0)
        return $this->attributes['xp'] ?? 0;
    }

    public function getStreakAttribute(): int
    {
        // Calculate day streak based on recent activity
        $lastActivity = $this->activityLogs()
            ->where('created_at', '>=', now()->subDays(30))
            ->max('created_at');

        if (! $lastActivity) {
            return 0;
        }

        // Simplified: return days since last activity (capped at 30)
        return min(30, now()->diffInDays($lastActivity));
    }

    public function enrolledCourses()
    {
        return Course::whereHas('enrollments', function ($query) {
            $query->where('user_id', $this->id);
        });
    }

    public function enrolledBootcamps()
    {
        return Bootcamp::whereHas('enrollments', function ($query) {
            $query->where('user_id', $this->id);
        });
    }

    public function getTopSkills(int $limit = 10): array
    {
        return $this->skills()
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->pluck('skill_name')
            ->toArray();
    }
}
