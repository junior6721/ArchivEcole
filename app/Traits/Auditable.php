<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Enums\AuditAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAudit(
                AuditAction::CREATE_DIPLOMA->value,
                $model->getKey(),
                null,
                $model->getAttributes()
            );
        });

        static::updated(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getAttributes();
            $changes = [];

            foreach ($newValues as $key => $value) {
                if ($oldValues[$key] ?? null !== $value) {
                    $changes[$key] = [
                        'old' => $oldValues[$key] ?? null,
                        'new' => $value,
                    ];
                }
            }

            if (!empty($changes)) {
                self::logAudit(
                    AuditAction::UPDATE_DIPLOMA->value,
                    $model->getKey(),
                    $oldValues,
                    $changes
                );
            }
        });

        static::deleted(function ($model) {
            self::logAudit(
                AuditAction::DELETE_DIPLOMA->value,
                $model->getKey(),
                $model->getAttributes(),
                null
            );
        });
    }

    public static function logAudit(string $action, $modelId, $oldValues = null, $newValues = null)
    {
        if (!config('archivecole.audit.enabled')) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => static::class,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'method' => Request::method(),
            'url' => Request::fullUrl(),
        ]);
    }
}
