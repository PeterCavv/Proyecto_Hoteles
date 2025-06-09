<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    case HOTEL = 'hotel';

    public static function getValues(): array
    {
        return [
            self::ADMIN,
            self::CUSTOMER,
            self::HOTEL,
        ];
    }
}
