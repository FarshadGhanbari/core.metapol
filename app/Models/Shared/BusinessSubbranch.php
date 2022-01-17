<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class BusinessSubbranch extends Model
{
    protected $connection = 'common_database';

    use Eloquence, Sluggable;

    protected $searchableColumns = [
        'name',
    ];

    protected $fillable = [
        'business_branch_id',
        'status',
        'name',
        'slug',
    ];

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\BusinessBranch::class);
    }
}
