<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Model PaymentVerification untuk verifikasi pembayaran oleh Keuangan
 */
class PaymentVerification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_verifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'bootcamp_id',
        'enrollment_id',
        'course_title',
        'course_type',
        'amount',
        'original_price',
        'discount_amount',
        'promo_code',
        'proof_image',
        'payment_method',
        'status',
        'verified_by',
        'verification_notes',
        'rejection_reason',
        'verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Course type constants
     */
    public const TYPE_COURSE = 'course';
    public const TYPE_BOOTCAMP = 'bootcamp';

    /**
     * Relasi ke user yang melakukan pembayaran
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke user yang memverifikasi (Keuangan)
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Relasi ke enrollment (jika sudah ada)
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Relasi ke kursus (jika ada)
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relasi ke bootcamp (jika ada)
     */
    public function bootcamp(): BelongsTo
    {
        return $this->belongsTo(Bootcamp::class);
    }

    /**
     * Scope: yang masih pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: yang sudah disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: yang ditolak
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope: berdasarkan tanggal
     */
    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Cek apakah sudah pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Cek apakah sudah disetujui
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Cek apakah ditolak
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Cek apakah ini enrollment kursus
     */
    public function isCourseType(): bool
    {
        return $this->course_type === self::TYPE_COURSE;
    }

    /**
     * Cek apakah ini enrollment bootcamp
     */
    public function isBootcampType(): bool
    {
        return $this->course_type === self::TYPE_BOOTCAMP;
    }

    /**
     * Approve payment and auto-enroll student
     * Follows the flow from role-flow-diagrams.md section 3
     */
    public function approve(string $notes = null): bool
    {
        return DB::transaction(function () use ($notes) {
            $this->update([
                'status' => self::STATUS_APPROVED,
                'verified_by' => auth()->id(),
                'verification_notes' => $notes,
                'verified_at' => now(),
            ]);

            // Log aktivitas
            AuditLog::log(
                AuditLog::ACTION_VERIFY,
                self::class,
                $this->id,
                ['status' => self::STATUS_PENDING],
                ['status' => self::STATUS_APPROVED],
                "Verifikasi pembayaran untuk {$this->course_title}"
            );

            // Auto-create enrollment (if not already created)
            $enrollment = $this->createEnrollment();

            // Create notification for student
            $this->notifyApproved($enrollment);

            return true;
        });
    }

    /**
     * Reject payment and notify student
     * Follows the flow from role-flow-diagrams.md section 3
     */
    public function reject(string $reason): bool
    {
        return DB::transaction(function () use ($reason) {
            $this->update([
                'status' => self::STATUS_REJECTED,
                'verified_by' => auth()->id(),
                'rejection_reason' => $reason,
                'verified_at' => now(),
            ]);

            // Log aktivitas
            AuditLog::log(
                AuditLog::ACTION_REJECT,
                self::class,
                $this->id,
                ['status' => self::STATUS_PENDING],
                ['status' => self::STATUS_REJECTED, 'reason' => $reason],
                "Tolak pembayaran untuk {$this->course_title}"
            );

            // Create notification for student
            $this->notifyRejected($reason);

            return true;
        });
    }

    /**
     * Create enrollment for the student
     */
    protected function createEnrollment(): ?Enrollment
    {
        // If already enrolled, return existing enrollment
        if ($this->enrollment_id) {
            return $this->enrollment;
        }

        // Determine purchasable type and id
        $purchasableType = $this->isCourseType() ? Course::class : Bootcamp::class;
        $purchasableId = $this->isCourseType() ? $this->course_id : $this->bootcamp_id;

        // Check if purchasable exists
        if (!$purchasableId) {
            // Fallback: try to find by title
            $purchasable = $this->isCourseType()
                ? Course::where('title', $this->course_title)->first()
                : Bootcamp::where('title', $this->course_title)->first();

            if ($purchasable) {
                $purchasableType = get_class($purchasable);
                $purchasableId = $purchasable->id;
            } else {
                // Cannot create enrollment without valid purchasable
                return null;
            }
        }

        // Check if enrollment already exists
        $existingEnrollment = Enrollment::where('user_id', $this->user_id)
            ->where('purchasable_type', $purchasableType)
            ->where('purchasable_id', $purchasableId)
            ->first();

        if ($existingEnrollment) {
            $this->update(['enrollment_id' => $existingEnrollment->id]);
            return $existingEnrollment;
        }

        // Create new enrollment
        $enrollment = Enrollment::create([
            'user_id' => $this->user_id,
            'purchasable_type' => $purchasableType,
            'purchasable_id' => $purchasableId,
            'status' => 'active',
        ]);

        // Update payment verification with enrollment id
        $this->update(['enrollment_id' => $enrollment->id]);

        return $enrollment;
    }

    /**
     * Notify student of approval and enrollment
     */
    protected function notifyApproved(?Enrollment $enrollment): void
    {
        $notification = app(NotificationService::class);

        if ($enrollment) {
            // Enrollment successful
            $link = $this->isCourseType()
                ? route('kursus-saya')
                : route('bootcamps-saya');

            $notification->create(
                $this->user_id,
                'payment_approved',
                '✅ Pembayaran Disetujui!',
                "Selamat! Kamu berhasil terdaftar di \"{$this->course_title}\". Sekarang kamu bisa mulai belajar!",
                [
                    'icon' => 'check-circle',
                    'color' => 'green',
                    'link' => $link,
                    'enrollment_id' => $enrollment->id,
                ]
            );
        } else {
            // Payment approved but enrollment could not be created
            $notification->create(
                $this->user_id,
                'payment_approved_no_enrollment',
                '✅ Pembayaran Disetujui',
                "Pembayaran untuk \"{$this->course_title}\" telah disetujui. Tim kami akan segera mendaftarkan kamu.",
                [
                    'icon' => 'check-circle',
                    'color' => 'green',
                ]
            );
        }
    }

    /**
     * Notify student of rejection with reason
     */
    protected function notifyRejected(string $reason): void
    {
        $notification = app(NotificationService::class);

        $notification->create(
            $this->user_id,
            'payment_rejected',
            '❌ Pembayaran Ditolak',
            "Pembayaran untuk \"{$this->course_title}\" ditolak. Alasan: {$reason}. Silakan upload bukti pembayaran yang benar.",
            [
                'icon' => 'x-circle',
                'color' => 'red',
                'link' => route('pembayaran'),
                'rejection_reason' => $reason,
            ]
        );
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            self::STATUS_PENDING => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => 'Menunggu'],
            self::STATUS_APPROVED => ['class' => 'bg-green-100 text-green-800', 'label' => 'Disetujui'],
            self::STATUS_REJECTED => ['class' => 'bg-red-100 text-red-800', 'label' => 'Ditolak'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'label' => 'Unknown'],
        };
    }
}
