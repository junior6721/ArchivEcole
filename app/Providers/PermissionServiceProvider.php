<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Create or get roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $institutionAdminRole = Role::firstOrCreate(['name' => 'institution_admin']);
        $agentRole = Role::firstOrCreate(['name' => 'agent']);
        $verifierRole = Role::firstOrCreate(['name' => 'verifier']);

        // Create permissions
        $permissions = [
            // Institution permissions
            'manage-institutions',
            'view-institutions',
            'create-institution',
            'edit-institution',
            'delete-institution',

            // User permissions
            'manage-users',
            'view-users',
            'create-user',
            'edit-user',
            'delete-user',

            // Diploma permissions
            'manage-diplomas',
            'view-diplomas',
            'create-diploma',
            'edit-diploma',
            'delete-diploma',
            'import-diplomas',

            // Verification permissions
            'verify-diploma',
            'view-verification-requests',
            'manage-verification-requests',

            // Payment permissions
            'view-payments',
            'manage-payments',

            // Audit permissions
            'view-audit-logs',

            // Settings permissions
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $superAdminRole->givePermissionTo(Permission::all());

        $institutionAdminPermissions = [
            'view-users', 'create-user', 'edit-user', 'delete-user',
            'manage-diplomas', 'view-diplomas', 'create-diploma', 'edit-diploma', 'delete-diploma', 'import-diplomas',
            'view-verification-requests', 'manage-verification-requests',
            'view-payments',
            'view-audit-logs',
        ];
        $institutionAdminRole->syncPermissions($institutionAdminPermissions);

        $agentPermissions = [
            'view-diplomas', 'create-diploma', 'edit-diploma', 'import-diplomas',
            'view-verification-requests',
        ];
        $agentRole->syncPermissions($agentPermissions);

        $verifierPermissions = [
            'verify-diploma',
        ];
        $verifierRole->syncPermissions($verifierPermissions);
    }
}
