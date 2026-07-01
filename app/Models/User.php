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
        'email',
        'password',
        'role',
        'profile_photo',
        'bio',
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
        // Calculate XP from completions and activities
        $completionXp = $this->completions()->count() * 100;
        $activityXp = $this->activityLogs()->count() * 10;
        $ratingXp = $this->courseRatings()->count() * 50;
        $attendanceXp = $this->attendanceRecords()->where('verified', true)->count() * 25;

        return $completionXp + $activityXp + $ratingXp + $attendanceXp;
    }

    public function getStreakAttribute(): int
    {
        // Calculate day streak based on recent activity
        $lastActivity = $this->activityLogs()
            ->where('created_at', '>=', now()->subDays(30))
            ->max('created_at');

        if (!$lastActivity) {
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
