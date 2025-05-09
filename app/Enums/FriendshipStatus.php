<?php

namespace App\Enums;

enum FriendshipStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case BLOCKED = 'blocked';

    public static function getValues(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::BLOCKED,
        ];
    }
}
