<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;

class Filemanager extends Model
{
    protected $connection = 'common_database';

    protected $fillable = [
        'user_id',
        'src',
    ];

    protected $hidden = [
        'src',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\User::class);
    }

    public function folders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FilemanagerFolder::class);
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FilemanagerFile::class);
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->folders()->delete();
        });
    }
}
