<?php

namespace App\Policies;

use App\Models\Log;
use App\Models\Shared\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): bool
    {
        return $user->isAdministrator();
    }

    public function viewAny(User $user): bool
    {
        return in_array('logs list', $user->allPermissions());
    }

    public function view(User $user, Log $model): bool
    {
        return in_array('logs show', $user->allPermissions());
    }
}
