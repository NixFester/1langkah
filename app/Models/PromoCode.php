<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PromoCode untuk menyimpan kode promo dari Marketing
 */
class PromoCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'max_uses',
        'used_count',
        'min_purchase',
        'max_discount',
        'starts_at',
        'expires_at',
        'is_active',
        'created_by',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'integer',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'starts_at' => 'date',
        'expires_at' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Type constants
     */
    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed_amount';

    /**
     * Relasi ke user yang membuat promo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: promo yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: promo yang belum kadaluarsa
     */
    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now()->toDateString());
        });
    }

    /**
     * Scope: promo yang sudah dimulai
     */
    public function scopeStarted($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('starts_at')
              ->orWhere('starts_at', '<=', now()->toDateString());
        });
    }

    /**
     * Scope: promo yang masih bisa digunakan
     */
    public function scopeAvailable($query)
    {
        return $query->active()->valid()->started()
            ->where(function ($q) {
                $q->whereNull('max_uses')
                  ->orWhereColumn('used_count', '<', 'max_uses');
            });
    }

    /**
     * Cek apakah promo masih valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->lt(now())) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->gt(now())) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Hitung diskon untuk harga tertentu
     */
    public function calculateDiscount(float $price): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->min_purchase && $price < $this->min_purchase) {
            return 0;
        }

        $discount = match($this->type) {
            self::TYPE_PERCENTAGE => ($price * $this->value) / 100,
            self::TYPE_FIXED => $this->value,
            default => 0,
        };

        // Batasi maksimal diskon jika ada
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        // Jangan melebihi harga
        return min($discount, $price);
    }

    /**
     * Generate kode promo acak
     */
    public static function generateCode(int $length = 8): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * Tambah penggunaan
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_PERCENTAGE => $this->value . '%',
            self::TYPE_FIXED => 'Rp ' . number_format($this->value),
            default => $this->type,
        };
    }

    /**
     * Get usage percentage
     */
    public function getUsagePercentageAttribute(): float
    {
        if (!$this->max_uses) {
            return 0;
        }
        return ($this->used_count / $this->max_uses) * 100;
    }

    /**
     * Get remaining uses
     */
    public function getRemainingUsesAttribute(): ?int
    {
        if (!$this->max_uses) {
            return null;
        }
        return max(0, $this->max_uses - $this->used_count);
    }
}
