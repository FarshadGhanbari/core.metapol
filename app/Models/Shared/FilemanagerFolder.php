<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\SubmitLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class FilemanagerFolder extends Model
{
    protected $connection = 'common_database';

    use SubmitLogger;

    protected $fillable = [
        'filemanager_id',
        'user_id',
        'src',
        'name',
    ];

    protected $hidden = [
        'filemanager_id',
        'user_id',
        'src',
        'created_at',
        'updated_at',
    ];

    public function filemanager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\Filemanager::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\User::class);
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FilemanagerFile::class);
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->files()->delete();
            File::deleteDirectory(public_path($row->src));
        });
    }
}
