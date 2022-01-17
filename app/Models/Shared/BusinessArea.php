<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class BusinessArea extends Model
{
    protected $connection = 'common_database';

    use Eloquence, Sluggable;

    protected $searchableColumns = [
        'name',
    ];

    protected $fillable = [
        'status',
        'name',
        'slug',
    ];

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Shared\BusinessCategory::class);
    }

    public function branches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BusinessBranch::class);
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->branches()->delete();
        });
    }
}
