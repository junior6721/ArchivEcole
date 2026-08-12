<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Institution extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
        'website',
        'contact_email',
        'contact_phone',
        'address',
        'city',
        'country',
        'registration_number',
        'tax_id',
        'logo_path',
        'seal_path',
        'status',
        'subscription_tier',
        'diplomas_limit',
        'users_limit',
        'metadata',
        'activated_at',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'metadata' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Str::uuid();
            }
            if (!$model->slug) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function diplomas(): HasMany
    {
        return $this->hasMany(Diploma::class);
    }

    public function verificationRequests(): HasMany
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function canAddDiploma(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->diplomas_limit === null) {
            return true;
        }

        return $this->diplomas()->count() < $this->diplomas_limit;
    }

    public function canAddUser(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->users_limit === null) {
            return true;
        }

        return $this->users()->where('is_active', true)->count() < $this->users_limit;
    }
}
