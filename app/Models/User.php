<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use App\Enums\UserRole;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'phone',
        'photo_url',
        'institution_id',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Str::uuid();
            }
        });
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function createdDiplomas(): HasMany
    {
        return $this->hasMany(Diploma::class, 'created_by_id');
    }

    public function reviewedDiplomas(): HasMany
    {
        return $this->hasMany(Diploma::class, 'reviewed_by_id');
    }

    public function verificationRequests(): HasMany
    {
        return $this->hasMany(VerificationRequest::class, 'verified_by_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    public function isInstitutionAdmin(): bool
    {
        return $this->role === UserRole::INSTITUTION_ADMIN;
    }

    public function isAgent(): bool
    {
        return $this->role === UserRole::AGENT;
    }

    public function isVerifier(): bool
    {
        return $this->role === UserRole::VERIFIER;
    }

    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    public function recordLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }
}
