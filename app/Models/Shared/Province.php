<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\Sluggable;
use App\Utilis\Traits\Model\SubmitLogger;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Province extends Model
{
    protected $connection = 'common_database';

    use Eloquence, SubmitLogger, Sluggable;

    protected $searchableColumns = [
        'country.name',
        'name',
    ];

    protected $fillable = [
        'country_id',
        'name',
        'slug',
        'status',
    ];

    protected $appends = [
        'country_name',
    ];

    public function getCountryNameAttribute()
    {
        return $this->country->name;
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function cities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(City::class);
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
            $row->cities()->delete();
        });
    }
}
