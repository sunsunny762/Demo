<?php
namespace App\Traits;

use Auth;
use Cache;
use Carbon;
use App\Events\CreateAction;
use App\Events\UpdateAction;

trait DbEvents
{

    /**
     * Save created_by & updated_by events on creating / updating / deleting event
     */
    protected static function bootDbEvents()
    {
        static::creating(function ($model) {
            // Save created_by on creating event
            if (!empty($model->has_created_by)) {
                $model->created_by = !empty(Auth::id()) ? Auth::id() : 0;
            }

            // Save updated_by on creating event
            if (!empty($model->has_updated_by)) {
                $model->updated_by = !empty(Auth::id()) ? Auth::id() : 0;
            }
        });

        static::created(function ($model) {
            // Flush Cache on Creation of Model
            self::flushCache($model);

            event(new CreateAction($model));
        });


        static::updating(function ($model) {
            // Save updated_by on updating event
            if (!empty($model->has_updated_by)) {
                $model->updated_by = !empty(Auth::id()) ? Auth::id() : 0;
            }
        });

        static::updated(function ($model) {
            // Flush cache on update of Model
            self::flushCache($model);
            event(new UpdateAction($model));
        });

        // Save updated_by on deleting event
        static::deleting(function ($model) {
            if (!empty($model->has_updated_by)) {
                $model->updated_by = !empty(Auth::id()) ? Auth::id() : 0;
            }
        });

        static::deleted(function ($model) {
            // Flush cache on update of Model
            self::flushCache($model);
        });
    }

    /**
     * Flush the cache for specific model
     */
    protected static function flushCache($model)
    {
        // Forget Cache after creating
        if (!empty($model->cache)) {
            foreach ($model->cache as $cache) {
                if (Cache::has($cache)) {
                    Cache::forget($cache);
                }
            }
        }
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format(config('app.datetime_format'));
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format(config('app.datetime_format'));
    }
}
