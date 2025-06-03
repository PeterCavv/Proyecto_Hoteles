<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

trait OwnerManagerPolicy
{
    use HandlesAuthorization;

    public function canManage(User $user, $model): bool
    {
        if(isset($model->user_id)) {
            return $user->id === $model->user_id || $user->isAdmin();
        }

        return false;
    }
}
