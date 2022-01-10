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
        'department',
    ];

    public $searchable = [
        'name',
        'department',
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

    public function departmentBadge(): string
    {
        $background = $this->department == 'not' ? 'bg-white dark:bg-trueGray-800 text-coolGray-500 dark:text-trueGray-500 border border-coolGray-200 dark:border-trueGray-900' : 'bg-emerald-500';
        return '<span class="transition-all py-1 px-3 inline-flex text-xs rounded-full ' . $background . ' text-white">' . __(ucfirst($this->department)) . '</span>';
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
