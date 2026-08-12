<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Enums\PaymentStatus;

class PaymentTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'transaction_number',
        'verification_request_id',
        'amount',
        'currency',
        'status',
        'provider',
        'provider_reference',
        'provider_request',
        'provider_response',
        'paid_at',
        'failed_at',
        'failure_reason',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'provider_request' => 'json',
        'provider_response' => 'json',
        'metadata' => 'json',
        'status' => PaymentStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Str::uuid();
            }
            if (!$model->transaction_number) {
                $model->transaction_number = self::generateTransactionNumber();
            }
        });
    }

    public function verificationRequest(): BelongsTo
    {
        return $this->belongsTo(VerificationRequest::class);
    }

    public static function generateTransactionNumber(): string
    {
        $prefix = 'TXN';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(Str::random(6));
        return "{$prefix}-{$timestamp}-{$random}";
    }

    public function isPending(): bool
    {
        return $this->status === PaymentStatus::PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->status === PaymentStatus::PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->status === PaymentStatus::COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === PaymentStatus::FAILED;
    }

    public function markAsCompleted(?string $providerReference = null): void
    {
        $this->update([
            'status' => PaymentStatus::COMPLETED,
            'paid_at' => now(),
            'provider_reference' => $providerReference ?? $this->provider_reference,
        ]);
    }

    public function markAsFailed(string $reason = null): void
    {
        $this->update([
            'status' => PaymentStatus::FAILED,
            'failed_at' => now(),
            'failure_reason' => $reason,
        ]);
    }
}
