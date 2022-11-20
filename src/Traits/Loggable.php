<?php

namespace Rasid\Activity\Traits;

use Illuminate\Support\Facades\DB;
use Rasid\Activity\Models\Log;

trait Loggable
{
    static function logToDb($model, $logType)
    {
        if (!auth()->check() || $model->excludeLogging || !config('activity.activated', true)) return;

        $originalData = $logType === 'create' ? json_encode($model) : json_encode($model->getRawOriginal());

        DB::table(config('activity.table'))->insert([
            'user_id'    => auth()->user()?->id,
            'log_date'   => date('Y-m-d H:i:s'),
            'subject_type' => get_class(),
            'subject_id' => $model->id,
            'ip_address' => request()->ip(),
            'log_type'   => $logType,
            'data'       => $originalData
        ]);
    }

    public static function bootLoggable()
    {
        if (config('activity.log_events.on_edit', false)) {
            self::updated(function ($model) {
                self::logToDb($model, 'edit');
            });
        }


        if (config('activity.log_events.on_delete', false)) {
            self::deleted(function ($model) {
                self::logToDb($model, 'delete');
            });
        }


        if (config('activity.log_events.on_create', false)) {
            self::created(function ($model) {
                self::logToDb($model, 'create');
            });
        }
    }

    public function activities()
    {
        return $this->morphMany(Log::class, 'subject');
    }
}
