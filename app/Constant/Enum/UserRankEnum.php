<?php

namespace App\Constant\Enum;

enum UserRankEnum:int
{
    case NGUOI_DUNG_HANG_NHAT = 1;
    case NGUOI_DUNG_HANG_HAI = 2;
    case NGUOI_DUNG_HANG_BA = 3;

    public function getRequiredMoney(): int
    {
        return match($this) {
            self::NGUOI_DUNG_HANG_NHAT => 2_000_000,
            self::NGUOI_DUNG_HANG_HAI => 5000000,
            self::NGUOI_DUNG_HANG_BA => 8000000,
        };
    }

    public function getRankName(): string
    {
        return match($this) {
            self::NGUOI_DUNG_HANG_NHAT => 'Người dùng hạng nhất',
            self::NGUOI_DUNG_HANG_HAI => 'Người dùng hạng hai',
            self::NGUOI_DUNG_HANG_BA => 'Người dùng hạng ba',
        };
    }
}
