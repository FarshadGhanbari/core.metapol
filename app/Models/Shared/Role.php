<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\Searchable;
use App\Utilis\Traits\Model\SubmitLogger;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'common_database';

    use SubmitLogger, Searchable;

    protected $fillable = [
        'name',
        'status',
    ];

    public $searchable = [
        'name',
    ];

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissionsRender(): string
    {
        if (in_array($this->id, [1, 2])) return __('All permissions');
        if ($this->id == 3) return __('Default');
        return implode(', ', $this->permissions->pluck('name')->toArray());
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->permissions()->detach();
            $row->users()->update(['role_id' => 3]);
        });
    }
}
