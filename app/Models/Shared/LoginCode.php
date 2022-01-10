<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;

class LoginCode extends Model
{
    protected $connection = 'common_database';

    protected $fillable = [
        'user_id',
        'code',
        'expiration_at',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\User::class);
    }

    public static function deleteExpired()
    {
        return static::where('expiration_at', '<=', now()->format('Y-m-d H:i:s'))->delete();
    }

    public static function createByUser($user_id, $code)
    {
        static::updateOrCreate(['user_id' => $user_id], ['code' => $code, 'expiration_at' => now()->addMinutes(2)]);
        return static::deleteExpired();
    }
}
