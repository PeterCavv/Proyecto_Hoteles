<?php

namespace App\Enums;

enum AttractionType: string
{
    case FREE = 'free';
    case PAY = 'pay';

    public static function getValues(): array
    {
        return [
            self::FREE,
            self::PAY,
        ];
    }
}
