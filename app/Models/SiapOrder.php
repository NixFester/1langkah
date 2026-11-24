<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiapOrder extends Model
{
    use HasFactory;

    public const PRODUCT_ASSESSMENT_FULL = 'assessment_full';
    public const PRODUCT_SUPER_FINGER = 'super_finger';
    public const PRODUCT_BUNDLE = 'bundle_siap';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_EXPIRED = 'expired';

    public const REDEEM_UNUSED = 'unused';
    public const REDEEM_USED = 'used';

    protected $fillable = [
        'user_id',
        'siap_assessment_id',
        'siap_attempt_id',
        'payment_transaction_id',
        'promo_code_id',
        'product_type',
        'product_name',
        'participant_name',
        'email',
        'parent_whatsapp',
        'parental_consent',
        'payment_method',
        'normal_price',
        'discount_amount',
        'final_price',
        'status',
        'redeem_code',
        'redeem_status',
        'redeemed_at',
        'redeemed_by',
        'fulfillment_notes',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'parental_consent' => 'boolean',
            'normal_price' => 'integer',
            'discount_amount' => 'integer',
            'final_price' => 'integer',
            'redeemed_at' => 'datetime',
            'fulfillment_notes' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(SiapAssessment::class, 'siap_assessment_id');
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(SiapAttempt::class, 'siap_attempt_id');
    }

    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function redeemedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemed_by');
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function includesRedeemCode(): bool
    {
        return in_array($this->product_type, [self::PRODUCT_SUPER_FINGER, self::PRODUCT_BUNDLE], true);
    }

    public function isBundle(): bool
    {
        return $this->product_type === self::PRODUCT_BUNDLE;
    }
}
