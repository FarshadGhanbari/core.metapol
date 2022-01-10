<?php

namespace App\Utilis\Traits\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Sluggable
{
    protected static function bootSluggable(): void
    {
        static::creating(fn(Model $model) => $model->generateSlug());
        static::updating(fn(Model $model) => $model->generateSlug());
    }

    protected function generateSlug(): void
    {
        $generateSlugFrom = $this->generateSlugFrom;
        $slugField = $this->slugField;
        $this->$slugField = $this->makeSlugUnique($this->$generateSlugFrom);
    }

    protected function makeSlugUnique(string $slug): string
    {
        $originalSlug = sluggable($slug);
        $i = 1;
        while ($this->otherRecordExistsWithSlug($slug) || $slug === '') {
            $slug = $originalSlug . '-' . $i++;
        }
        return $slug;
    }

    protected function otherRecordExistsWithSlug(string $slug): bool
    {
        $query = static::whereSlug($slug)->withoutGlobalScopes();
        if ($this->usesSoftDeletes()) {
            $query->withTrashed();
        }
        return $query->exists();
    }

    protected function usesSoftDeletes(): bool
    {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this), true);
    }

    public function scopeWhereSlug(Builder $scope, string $slug): Builder
    {
        return $scope->where('slug', $slug);
    }

    public static function findBySlug(string $slug, array $columns = ['*'])
    {
        return static::whereSlug($slug)->first($columns);
    }

    public static function findBySlugOrFail(string $slug, array $columns = ['*'])
    {
        return static::whereSlug($slug)->firstOrFail($columns);
    }
}
