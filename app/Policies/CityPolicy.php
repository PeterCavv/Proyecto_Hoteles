<?php

namespace App\Policies;

use App\Models\City;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CityPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, City $city): bool
    {
        return $user->isAdmin();
    }

    public function destroy(User $user, City $city): bool
    {
        return $user->isAdmin();
    }
}
