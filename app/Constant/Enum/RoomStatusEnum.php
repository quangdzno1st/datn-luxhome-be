<?php

namespace App\Constant\Enum;

enum RoomStatusEnum: int
{
    case KHONG_HOAT_DONG = 1;
    case DANG_SUA_CHUA = 2;
    case DANG_DON_DEP = 3;
    case SAN_SANG_SU_DUNG = 4;

    public static function isConstant(int $value): bool
    {
        return self::tryFrom($value) !== null;
    }
}
