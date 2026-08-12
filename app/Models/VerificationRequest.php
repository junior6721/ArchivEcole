<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Enums\VerificationStatus;

class VerificationRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'request_number',
        'diploma_id',
        'institution_id',
        'verifier_name',
        'verifier_email',
        'verifier_phone',
        'diploma_number',
        'student_name',
        'graduation_year',
        'status',
        'result',
        'payment_transaction_id',
        'verification_date',
        'verified_by_id',
        'notes',
        'audit_data',
        'expires_at',
    ];

    protected $casts = [
        'verification_date' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'audit_data' => 'json',
        'status' => VerificationStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Str::uuid();
            }
            if (!$model->request_number) {
                $model->request_number = self::generateRequestNumber();
            }
            if (!$model->expires_at) {
                $model->expires_at = now()->addHours(24);
            }
        });
    }

    public function diploma(): BelongsTo
    {
        return $this->belongsTo(Diploma::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_id');
    }

    public function otpCodes(): HasMany
    {
        return $this->hasMany(OtpCode::class);
    }

    public static function generateRequestNumber(): string
    {
        $prefix = 'VER';
        $year = now()->format('Y');
        $count = self::where('request_number', 'like', "{$prefix}-{$year}-%")->count() + 1;
        return "{$prefix}-{$year}-" . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    public function isPending(): bool
    {
        return $this->status === VerificationStatus::PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === VerificationStatus::COMPLETED;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canVerify(): bool
    {
        return !$this->isExpired() && $this->status !== VerificationStatus::COMPLETED;
    }
}
