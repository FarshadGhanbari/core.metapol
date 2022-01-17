<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class BusinessCategory extends Model
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

    public function areas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BusinessArea::class, 'category_id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->areas()->delete();
        });
    }
}
