<?php

namespace App\Constant\Enum;

enum UserRankEnum:int
{
    case VO_HANG = 0;
    case NGUOI_DUNG_HANG_NHAT = 1;
    case NGUOI_DUNG_HANG_HAI = 2;
    case NGUOI_DUNG_HANG_BA = 3;

    public function getRequiredMoney(): int
    {
        return match($this) {
            self::VO_HANG => 0,
            self::NGUOI_DUNG_HANG_NHAT => 2_000_000,
            self::NGUOI_DUNG_HANG_HAI => 5000000,
            self::NGUOI_DUNG_HANG_BA => 8000000,
        };
    }

    public function getRankName(): string
    {
        return match($this) {
            self::VO_HANG => 'Vô hạng',
            self::NGUOI_DUNG_HANG_NHAT => 'Người dùng hạng ba',
            self::NGUOI_DUNG_HANG_HAI => 'Người dùng hạng hai',
            self::NGUOI_DUNG_HANG_BA => 'Người dùng hạng nhất',
        };
    }

    public static function getRankByMoney(int $money): self
    {
        if ($money >= self::NGUOI_DUNG_HANG_BA->getRequiredMoney()) {
            return self::NGUOI_DUNG_HANG_BA;
        } elseif ($money >= self::NGUOI_DUNG_HANG_HAI->getRequiredMoney()) {
            return self::NGUOI_DUNG_HANG_HAI;
        } elseif ($money >= self::NGUOI_DUNG_HANG_NHAT->getRequiredMoney()) {
            return self::NGUOI_DUNG_HANG_NHAT;
        } else {
            return self::VO_HANG;  // Trả về "Vô hạng" khi tiền nhỏ hơn yêu cầu của tất cả các hạng
        }
    }
}
