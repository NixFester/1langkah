<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bootcamp_id',
        'attendance_date',
        'qr_code',
        'verified',
        'scanned_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'verified' => 'boolean',
        'scanned_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bootcamp(): BelongsTo
    {
        return $this->belongsTo(Bootcamp::class);
    }

    /**
     * Find attendance record by QR code
     */
    public static function findByQrCode(string $qrCode): ?self
    {
        return static::where('qr_code', $qrCode)->first();
    }

    /**
     * Verify attendance for a user
     */
    public static function verifyAttendance(int $userId, int $bootcampId, string $qrCode): array
    {
        $record = static::where('qr_code', $qrCode)
            ->where('bootcamp_id', $bootcampId)
            ->first();

        if (!$record) {
            return ['success' => false, 'message' => 'QR code not found for this bootcamp'];
        }

        if ($record->user_id !== $userId) {
            return ['success' => false, 'message' => 'This QR code belongs to another user'];
        }

        if ($record->verified) {
            return ['success' => false, 'message' => 'Attendance already verified'];
        }

        $record->update([
            'verified' => true,
            'scanned_at' => now(),
        ]);

        return ['success' => true, 'message' => 'Attendance verified successfully'];
    }
}
