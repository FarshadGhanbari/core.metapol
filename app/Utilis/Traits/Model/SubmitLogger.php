<?php

namespace App\Utilis\Traits\Model;

use App\Models\Shared\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait SubmitLogger
{
    static protected $ignoreList = [
        'id',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'created_at',
        'updated_at',
    ], $oldValues = [], $newValues = [];

    public static function bootSubmitLogger()
    {
        static::creating(function (Model $model) {
            foreach ($model->getAttributes() as $key => $value) {
                if (!in_array($key, self::$ignoreList) and !empty($value)) {
                    self::$newValues[$key] = [
                        'old' => null,
                        'new' => $key == 'password' ? '*******' : $value
                    ];
                }
            }
        });
        static::deleting(function (Model $model) {
            foreach ($model->getRawOriginal() as $key => $value) {
                if (!in_array($key, self::$ignoreList) and !empty($value)) {
                    self::$newValues[$key] = [
                        'old' => $key == 'password' ? '*******' : $value,
                        'new' => null
                    ];
                }
            }
        });
        static::updated(function (Model $model) {
            foreach ($model->getChanges() as $key => $value) {
                if (!in_array($key, self::$ignoreList)) {
                    self::$newValues[$key] = [
                        'old' => $key == 'password' ? '*******' : self::$oldValues[$key],
                        'new' => $key == 'password' ? '*******' : $value
                    ];
                }
            }
            static::storeLog($model, static::class, 'updated');
        });
        static::updating(fn (Model $model) => self::$oldValues = $model->getRawOriginal());
        static::created(fn (Model $model) => static::storeLog($model, static::class, 'created'));
        static::deleted(fn (Model $model) => static::storeLog($model, static::class, 'deleted'));
    }

    public static function getTagName(Model $model)
    {
        return !empty($model->tagName) ? $model->tagName : Str::title(Str::snake(class_basename($model), ' '));
    }

    public static function activeUserId()
    {
        return Auth::guard(static::activeUserGuard())->id();
    }

    public static function activeUserGuard()
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (auth()->guard($guard)->check()) {
                return $guard;
            }
        }
        return null;
    }

    public static function storeLog($model, $modelPath, $action)
    {
        if (count(self::$newValues) > 0) {
            Log::create([
                'user_id' => static::activeUserId(),
                'user_ip' => request()->ip(),
                'loggable_type' => $modelPath,
                'loggable_id' => $model->id,
                'action' => $action,
                'values' => self::$newValues,
            ]);
        }
    }
}
