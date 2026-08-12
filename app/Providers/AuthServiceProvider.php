<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Enums\UserRole;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Diploma' => 'App\Policies\DiplomaPolicy',
        // 'App\Models\Institution' => 'App\Policies\InstitutionPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates for role-based access
        Gate::define('super-admin', function (User $user) {
            return $user->role === UserRole::SUPER_ADMIN;
        });

        Gate::define('institution-admin', function (User $user) {
            return $user->role === UserRole::INSTITUTION_ADMIN && $user->institution_id !== null;
        });

        Gate::define('agent', function (User $user) {
            return $user->role === UserRole::AGENT && $user->institution_id !== null;
        });

        Gate::define('manage-institution', function (User $user, $institution) {
            return $user->isSuperAdmin() || 
                   ($user->isInstitutionAdmin() && $user->institution_id === $institution->id);
        });

        Gate::define('manage-diploma', function (User $user, $diploma) {
            return $user->isSuperAdmin() || 
                   ($user->institution_id === $diploma->institution_id && 
                    ($user->isInstitutionAdmin() || $user->isAgent()));
        });
    }
}
