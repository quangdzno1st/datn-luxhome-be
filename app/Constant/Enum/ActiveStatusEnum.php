<?php

namespace App\Constant\Enum;

enum ActiveStatusEnum: int
{
    case Active = 1;
    case InActive = 2;


    public static function isActive($value): bool
    {
        return self::tryFrom($value) === self::Active;
    }
}
