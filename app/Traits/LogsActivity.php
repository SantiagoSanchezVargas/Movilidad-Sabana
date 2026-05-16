<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logAction('create', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getAttributes();
            self::logAction('update', $model, $oldValues, $newValues);
        });

        static::deleted(function ($model) {
            self::logAction('delete', $model, $model->getAttributes(), null);
        });
    }

    private static function logAction($action, $model, $oldValues, $newValues)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action . '_' . class_basename($model),
            'model' => class_basename($model),
            'model_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}