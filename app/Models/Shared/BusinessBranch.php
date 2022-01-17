<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class BusinessBranch extends Model
{
    protected $connection = 'common_database';

    use Eloquence, Sluggable;

    protected $searchableColumns = [
        'name',
    ];

    protected $fillable = [
        'business_area_id',
        'status',
        'name',
        'slug',
    ];

    public function area(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\BusinessArea::class);
    }

    public function subbranches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BusinessSubbranch::class);
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->subbranches()->delete();
        });
    }
}
