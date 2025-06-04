<?php

namespace App\Observers;

use App\Models\User;

class ProfileObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function deleting(User $user): void
    {
        $user->syncRoles([]);
        $user->syncPermissions([]);
    }
}
