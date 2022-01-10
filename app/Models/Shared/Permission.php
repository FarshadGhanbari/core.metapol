<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'common_database';

    protected $fillable = [
        'name',
    ];

    public $searchable = [
        'name',
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function scopeWhereName(Builder $scope, string $name): Builder
    {
        return $scope->where('name', $name);
    }

    public static function findByName(string $name, array $columns = ['*'])
    {
        return static::whereName($name)->first($columns);
    }

    public static function findOrCreate(string $name)
    {
        $permission = static::findByName($name);
        if (!$permission) {
            return static::query()->create(['name' => $name]);
        }
        return $permission;
    }

    public static function deleteByName(string $name)
    {
        $permission = static::findByName($name);
        if ($permission) {
            $permission->roles()->detach();
            return $permission->delete();
        }
        return null;
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->users()->detach();
            $row->roles()->detach();
        });
    }
}
