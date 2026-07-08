<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorSchedule extends Model
{
    protected $fillable = [
        'mentor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_available' => 'boolean',
    ];

    // Day names mapping
    public const DAY_NAMES = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Get schedules for a specific mentor and day
     */
    public function scopeForMentorAndDay($query, int $mentorId, int $dayOfWeek)
    {
        return $query->where('mentor_id', $mentorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true);
    }

    // ── Static Helpers ───────────────────────────────────────────────────────

    /**
     * Check if mentor is available on a given date
     */
    public static function isAvailableOnDate(Mentor $mentor, Carbon $date): bool
    {
        $dayOfWeek = $date->dayOfWeek;

        return self::where('mentor_id', $mentor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->exists();
    }

    /**
     * Get available time slots for a mentor on a given date
     */
    public static function getTimeSlotsForDate(Mentor $mentor, Carbon $date): array
    {
        $dayOfWeek = $date->dayOfWeek;

        $schedules = self::where('mentor_id', $mentor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->get();

        // Generate hourly slots from start_time to end_time
        $slots = [];
        foreach ($schedules as $schedule) {
            $start = Carbon::parse($schedule->start_time);
            $end = Carbon::parse($schedule->end_time);

            while ($start < $end) {
                $slots[] = [
                    'time' => $start->format('H:i'),
                    'label' => $start->format('H:i').' WIB',
                    'available' => true,
                ];
                $start->addHour();
            }
        }

        return $slots;
    }

    /**
     * Get booked time slots for a mentor on a given date
     */
    public static function getBookedSlotsForDate(Mentor $mentor, Carbon $date): array
    {
        $bookedSessions = MentorSession::where('mentor_id', $mentor->id)
            ->where('booked_date', $date->toDateString())
            ->whereIn('status', [MentorSession::STATUS_PENDING, MentorSession::STATUS_ACTIVE])
            ->pluck('booked_time')
            ->toArray();

        return $bookedSessions;
    }

    /**
     * Get day name in Indonesian
     */
    public function getDayNameAttribute(): string
    {
        return self::DAY_NAMES[$this->day_of_week] ?? 'Unknown';
    }
}
