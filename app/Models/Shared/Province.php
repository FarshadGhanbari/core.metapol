<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\Searchable;
use App\Utilis\Traits\Model\Sluggable;
use App\Utilis\Traits\Model\SubmitLogger;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $connection = 'common_database';

    use SubmitLogger, Sluggable, Searchable;

    protected $generateSlugFrom = 'name', $slugField = 'slug';

    protected $fillable = [
        'country_id',
        'name',
        'status',
    ];

    public $searchable = [
        'name',
        'status',
    ];

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
