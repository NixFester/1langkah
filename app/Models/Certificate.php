<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'certificate_number',
        'title',
        'description',
        'certifiable_type',
        'certifiable_id',
        'issued_date',
        'valid_until',
        'verification_code',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'issued_date' => 'date',
            'valid_until' => 'date',
            'is_verified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function certifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Generate a unique certificate number
     */
    public static function generateCertificateNumber(): string
    {
        $year = date('Y');
        $random = strtoupper(Str::random(8));

        return "1L-{$year}-{$random}";
    }

    /**
     * Generate a unique verification code
     */
    public static function generateVerificationCode(): string
    {
        return strtoupper(Str::random(12));
    }

    /**
     * Create a certificate for a user
     */
    public static function createForUser(
        User $user,
        string $title,
        string $certifiableType,
        int $certifiableId,
        ?string $description = null,
        ?\DateTime $validUntil = null
    ): self {
        return self::create([
            'user_id' => $user->id,
            'certificate_number' => self::generateCertificateNumber(),
            'title' => $title,
            'description' => $description,
            'certifiable_type' => $certifiableType,
            'certifiable_id' => $certifiableId,
            'issued_date' => now(),
            'valid_until' => $validUntil,
            'verification_code' => self::generateVerificationCode(),
            'is_verified' => true,
        ]);
    }

    /**
     * Get the download URL
     */
    public function getDownloadUrl(): string
    {
        return route('certificates.download', ['id' => $this->id]);
    }

    /**
     * Get verification URL
     */
    public function getVerificationUrl(): string
    {
        return route('certificates.verify', ['code' => $this->verification_code]);
    }

    /**
     * Check if certificate is valid
     */
    public function isValid(): bool
    {
        if (! $this->is_verified) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        return true;
    }
}
