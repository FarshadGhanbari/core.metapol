<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Sofa\Eloquence\Eloquence;

class Statistic extends Model
{
    protected $connection = 'common_database';

    use Eloquence;

    protected $searchableColumns = [
        'user.first_name',
        'user.last_name',
        'ip',
        'geo',
        'referer_page',
        'page',
        'device',
        'device_type',
        'platform',
        'platform_version',
        'browser',
        'browser_version',
    ];

    protected $fillable = [
        'user_id',
        'ip',
        'geo',
        'referer_page',
        'page',
        'device',
        'device_type',
        'platform',
        'platform_version',
        'browser',
        'browser_version',
    ];

    protected $casts = [
        'geo' => AsCollection::class,
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\User::class);
    }

    protected static function booted()
    {
        static::creating(function ($row) {
            $row->user_id = Auth::id();
        });
        static::updating(function ($row) {
            $row->user_id = Auth::id();
        });
    }
}
