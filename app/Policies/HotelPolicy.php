<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\User;
use App\Traits\OwnerManagerPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class HotelPolicy
{
    use HandlesAuthorization, OwnerManagerPolicy;

    public function update(User $user, Hotel $hotel): bool
    {
        return $this->canManage($user, $hotel);
    }
}
