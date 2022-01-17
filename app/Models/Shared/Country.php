<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\Sluggable;
use App\Utilis\Traits\Model\SubmitLogger;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Country extends Model
{
    protected $connection = 'common_database';

    use Eloquence, SubmitLogger, Sluggable;

    protected $searchableColumns = [
        'name',
    ];

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    public function provinces(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Province::class);
    }

    public function statusBadge(): string
    {
        $background = $this->status == 'disable' ? 'bg-rose-500' : 'bg-emerald-500';
        return '<span class="transition-all py-1 px-3 inline-flex text-xs rounded-full ' . $background . ' text-white">' . __(ucfirst($this->status)) . '</span>';
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->provinces()->delete();
        });
    }
}
