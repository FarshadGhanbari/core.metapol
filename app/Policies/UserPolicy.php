<?php

namespace App\Policies;

use App\Models\Shared\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): bool
    {
        return $user->isAdministrator();
    }

    public function viewAny(User $user): bool
    {
        return in_array('users list', $user->allPermissions());
    }

    public function view(User $user, User $model): bool
    {
        return in_array('users show', $user->allPermissions());
    }

    public function create(User $user): bool
    {
        return in_array('users create', $user->allPermissions());
    }

    public function update(User $user, User $model): bool
    {
        return in_array('users update', $user->allPermissions());
    }

    public function delete(User $user, User $model): bool
    {
        return in_array('users delete', $user->allPermissions());
    }
}
