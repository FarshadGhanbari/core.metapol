<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\DateSwitcher;
use App\Utilis\Traits\Model\Searchable;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Statistic extends Model
{
    protected $connection = 'common_database';

    use DateSwitcher, Searchable;

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

    public $searchable = [
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
