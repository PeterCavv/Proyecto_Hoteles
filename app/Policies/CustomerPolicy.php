<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\OwnerManagerPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization, OwnerManagerPolicy;

    public function index(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, $customer): bool
    {
        return $this->canManage($user, $customer);
    }

    public function destroy(User $user, $customer): bool
    {
        return $this->canManage($user, $customer);
    }

}
