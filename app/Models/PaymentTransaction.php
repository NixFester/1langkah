<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'external_id',
        'payment_id',
        'driver',
        'item_type',
        'item_id',
        'amount',
        'status',
        'item_name',
        'metadata',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'PAID';
    }

    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    public function isExpired(): bool
    {
        return $this->status === 'EXPIRED';
    }

    public function isFailed(): bool
    {
        return in_array($this->status, ['FAILED', 'DECLINED']);
    }
}
