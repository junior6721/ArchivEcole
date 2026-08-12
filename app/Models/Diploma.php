<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Enums\DiplomaStatus;

class Diploma extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'unique_identifier',
        'institution_id',
        'student_id',
        'diploma_number',
        'title',
        'major',
        'minor',
        'specialization',
        'graduation_date',
        'academic_year',
        'grade',
        'mention',
        'status',
        'file_path',
        'file_mime_type',
        'signature_path',
        'qr_code_token',
        'qr_code_data',
        'created_by_id',
        'reviewed_by_id',
        'reviewed_at',
        'metadata',
    ];

    protected $casts = [
        'graduation_date' => 'date',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'metadata' => 'json',
        'status' => DiplomaStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Str::uuid();
            }
            if (!$model->unique_identifier) {
                $model->unique_identifier = self::generateUniqueIdentifier();
            }
        });
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_id');
    }

    public function verificationRequests(): HasMany
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public static function generateUniqueIdentifier(): string
    {
        $prefix = 'ARC';
        $timestamp = now()->format('Y');
        $random = strtoupper(Str::random(12));
        return "{$prefix}-{$timestamp}-{$random}";
    }

    public function isArchived(): bool
    {
        return $this->status === DiplomaStatus::ARCHIVED;
    }

    public function isVerified(): bool
    {
        return $this->status === DiplomaStatus::VERIFIED;
    }

    public function isRevoked(): bool
    {
        return $this->status === DiplomaStatus::REVOKED;
    }

    public function isPendingReview(): bool
    {
        return $this->status === DiplomaStatus::PENDING_REVIEW;
    }
}
