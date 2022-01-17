<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Log extends Model
{
    protected $connection = 'common_database';

    use Eloquence;

    protected $searchableColumns = [
        'user.first_name',
        'user.last_name',
        'user_ip',
        'action',
        'values',
    ];

    protected $fillable = [
        'user_id',
        'user_ip',
        'loggable_type',
        'loggable_id',
        'action',
        'values',
    ];

    protected $casts = [
        'values' => AsCollection::class,
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\User::class);
    }

    public function loggable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function actionBadge(): string
    {
        $background = $this->action == 'deleted' ? 'bg-rose-500' : ($this->action == 'created' ? 'bg-emerald-500' : 'bg-blue-500');
        return '<span class="transition-all py-1 px-3 inline-flex text-xs rounded-full ' . $background . ' text-white">' . __(ucfirst($this->action)) . '</span>';
    }
}
