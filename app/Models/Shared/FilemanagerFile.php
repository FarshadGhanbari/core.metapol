<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class FilemanagerFile extends Model
{
    protected $connection = 'common_database';

    protected $fillable = [
        'filemanager_id',
        'filemanager_folder_id',
        'user_id',
        'src',
        'name',
        'extension',
        'size',
    ];

    protected $hidden = [
        'filemanager_id',
        'filemanager_folder_id',
        'user_id',
        'created_at',
        'extension',
    ];

    protected $appends = [
        'icon',
    ];

    public function getIconAttribute(): array
    {
        $svg = [
            'pdf' => '<i class="fa fa-file-pdf"></i>',
            'zip' => '<i class="fa fa-file-zipper"></i>',
        ];
        $extensions = ['gif', 'jpg', 'jpeg', 'png', 'tiff', 'tif'];
        $extension = pathinfo(public_path($this->src), PATHINFO_EXTENSION);
        if (in_array($extension, $extensions)) {
            return [
                'type' => 'image',
                'src' => $this->src,
            ];
        }
        return [
            'type' => 'file',
            'src' => $svg[$extension],
        ];
    }

    public function filemanager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\Filemanager::class);
    }

    public function folder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\FilemanagerFolder::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Shared\User::class);
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            File::delete(public_path($row->src));
        });
    }
}
