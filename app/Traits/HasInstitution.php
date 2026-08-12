<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasInstitution
{
    public function scopeForInstitution(Builder $query, $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    public function scopeForCurrentInstitution(Builder $query)
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query->whereNull('institution_id');
        }

        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->where('institution_id', $user->institution_id);
    }
}
