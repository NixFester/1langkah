<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model AuditLog untuk mencatat semua aktivitas user di sistem
 *
 * Mencatat: created, updated, deleted, verified, login, logout, dll
 */
class AuditLog extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     * Kami hanya menggunakan created_at, bukan updated_at
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Action constants untuk jenis aktivitas
     */
    public const ACTION_CREATE = 'created';

    public const ACTION_UPDATE = 'updated';

    public const ACTION_DELETE = 'deleted';

    public const ACTION_VERIFY = 'verified';

    public const ACTION_REJECT = 'rejected';

    public const ACTION_LOGIN = 'login';

    public const ACTION_LOGOUT = 'logout';

    public const ACTION_ROLE_CHANGE = 'role_changed';

    public const ACTION_ENROLL = 'enrolled';

    public const ACTION_PAYMENT = 'payment_processed';

    /**
     * Relasi ke user yang melakukan aktivitas
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk filter berdasarkan action
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan model type
     */
    public function scopeForModel($query, string $modelType, ?int $modelId = null)
    {
        $query->where('model_type', $modelType);
        if ($modelId !== null) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope untuk aktivitas terbaru
     */
    public function scopeLatest($query, int $limit = 50)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Static method untuk mencatat aktivitas
     *
     * @param  string  $action  Jenis aktivitas
     * @param  string|null  $modelType  Model yang dimodifikasi
     * @param  int|null  $modelId  ID record yang dimodifikasi
     * @param  array|null  $oldValues  Data sebelum diubah
     * @param  array|null  $newValues  Data setelah diubah
     * @param  string|null  $description  Deskripsi tambahan
     */
    public static function log(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Get human-readable action label
     */
    public function getActionLabelAttribute(): string
    {
        return __('app.action_'.$this->action);
    }

    /**
     * Get icon class based on action
     */
    public function getActionIconAttribute(): string
    {
        $icons = [
            'created' => 'fa-plus-circle text-success',
            'updated' => 'fa-edit text-warning',
            'deleted' => 'fa-trash text-danger',
            'verified' => 'fa-check-circle text-success',
            'rejected' => 'fa-times-circle text-danger',
            'login' => 'fa-sign-in-alt text-info',
            'logout' => 'fa-sign-out-alt text-secondary',
            'role_changed' => 'fa-user-shield text-primary',
            'enrolled' => 'fa-graduation-cap text-primary',
            'payment_processed' => 'fa-credit-card text-success',
        ];

        return $icons[$this->action] ?? 'fa-circle text-secondary';
    }
}
