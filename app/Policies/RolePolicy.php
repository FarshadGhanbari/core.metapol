<?php

namespace App\Policies;

use App\Models\Shared\Role;
use App\Models\Shared\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): bool
    {
        return $user->isAdministrator();
    }

    public function viewAny(User $user): bool
    {
        return in_array('roles list', $user->allPermissions());
    }

    public function view(User $user, Role $model): bool
    {
        return in_array('roles show', $user->allPermissions());
    }

    public function create(User $user): bool
    {
        return in_array('roles create', $user->allPermissions());
    }

    public function update(User $user, Role $model): bool
    {
        return in_array('roles update', $user->allPermissions());
    }

    public function delete(User $user, Role $model): bool
    {
        return in_array('roles delete', $user->allPermissions());
    }
}
