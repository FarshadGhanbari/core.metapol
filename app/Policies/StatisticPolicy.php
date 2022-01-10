<?php

namespace App\Policies;

use App\Models\Statistic;
use App\Models\Shared\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatisticPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): bool
    {
        return $user->isAdministrator();
    }

    public function viewAny(User $user): bool
    {
        return in_array('statistics list', $user->allPermissions());
    }

    public function view(User $user, Statistic $model): bool
    {
        return in_array('statistics show', $user->allPermissions());
    }
}
