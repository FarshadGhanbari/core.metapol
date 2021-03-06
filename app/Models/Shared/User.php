<?php

namespace App\Models\Shared;

use App\Utilis\Traits\Model\SubmitLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Sofa\Eloquence\Eloquence;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $connection = 'common_database';

    use Eloquence, SubmitLogger, Notifiable;

    protected $searchableColumns = [
        'role.name',
        'first_name',
        'last_name',
        'mobile',
        'email',
    ];

    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'mobile',
        'email',
        'password',
        'status',
        'additions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'role',
    ];

    protected $casts = [
        'additions' => 'array',
    ];

    protected $appends = [
        'name',
        'role_name',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getNameAttribute()
    {
        return $this->first_name ? $this->first_name . ' ' . $this->last_name : 'کاربر';
    }

    public function getRoleNameAttribute()
    {
        return $this->role->name;
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function loginCode(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(LoginCode::class);
    }

    public function logs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function filemanager(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(Filemanager::class);
    }

    public function filemanagerFolders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FilemanagerFolder::class);
    }

    public function filemanagerFiles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FilemanagerFile::class);
    }

    public function statistics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Statistic::class);
    }

    public function allPermissions()
    {
        return $this->role->permissions->merge($this->permissions)->pluck('name')->toArray();
    }

    public function isAdministrator(): bool
    {
        return ($this->role->name == 'developer' or $this->role->name == 'admin');
    }

    public function initDefaultData()
    {
        $filemanagerSrc = '/storage/filemanagers/' . makeHash(now() . $this->id);
        $filemanagerFolderSrc = $filemanagerSrc . '/' . makeHash('public');
        $filemanager = Filemanager::create(['user_id' => $this->id, 'src' => $filemanagerSrc]);
        return FilemanagerFolder::create(['filemanager_id' => $filemanager->id, 'user_id' => $this->id, 'src' => $filemanagerFolderSrc, 'name' => 'public']);
    }

    public function scopeWhereSlug(Builder $scope, string $mobile): Builder
    {
        return $scope->where('mobile', $mobile);
    }

    public static function findByMobile(string $mobile, array $columns = ['*'])
    {
        return static::whereMobile($mobile)->first($columns);
    }

    public static function findByMobileOrFail(string $mobile, array $columns = ['*'])
    {
        return static::whereMobile($mobile)->firstOrFail($columns);
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->permissions()->detach();
            $row->logs()->delete();
            $row->filemanager()->delete();
            $row->statistics()->update(['user_id' => null]);
        });
        static::creating(function ($row) {
            $row->password = Hash::make($row->password);
        });
        static::updating(function ($row) {
            if (!empty($row->password)) {
                $row->password = Hash::make($row->password);
            }
        });
        static::created(function ($row) {
            $row->initDefaultData();
        });
    }
}
