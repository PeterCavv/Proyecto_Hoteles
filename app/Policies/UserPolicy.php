<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function impersonate(User $authUser, User $targetUser): bool
    {
        return $authUser->isAdmin() && ! $targetUser->isAdmin();
    }

    public function index(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, User $targetUser): bool
    {
        return $user->id === $targetUser->id || $user->isAdmin();
    }
}
