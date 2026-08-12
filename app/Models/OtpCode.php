<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OtpCode extends Model
{
    protected $fillable = [
        'uuid',
        'verification_request_id',
        'code',
        'channel',
        'recipient',
        'attempts',
        'max_attempts',
        'expires_at',
        'verified_at',
        'ip_address',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'metadata' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Str::uuid();
            }
            if (!$model->code) {
                $model->code = self::generateCode();
            }
            if (!$model->expires_at) {
                $model->expires_at = now()->addMinutes(15);
            }
        });
    }

    public function verificationRequest(): BelongsTo
    {
        return $this->belongsTo(VerificationRequest::class);
    }

    public static function generateCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function canAttempt(): bool
    {
        return $this->attempts < $this->max_attempts && !$this->isExpired();
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    public function markAsVerified(): void
    {
        $this->update(['verified_at' => now()]);
    }
}
