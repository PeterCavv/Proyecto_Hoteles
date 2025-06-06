<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreatedUserEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
