<?php

namespace App\Constant\Enum;

enum StatusOrderEnum: int
{
    case DANG_CHO = 1;
    case DA_XAC_NHAN = 2;
    case HOAN_THANH = 3;
    case DA_HUY = 4;
    case YEU_CAU_HUY = 5;

    public function getName(): string
    {
        return match ($this) {
            self::DANG_CHO => 'Đang chờ',
            self::DA_XAC_NHAN => 'Đã xác nhận',
            self::HOAN_THANH => 'Hoàn thành',
            self::DA_HUY => 'Đã hủy',
            self::YEU_CAU_HUY => 'Yêu cầu hủy',
        };
    }

    public static function parse(int $value): ?self
    {
        return self::tryFrom($value);
    }

    public static function isDangCho($value): bool
    {
        return self::tryFrom($value) === self::DANG_CHO;
    }

    public static function isDaXacNhan($value): bool
    {
        return self::tryFrom($value) === self::DA_XAC_NHAN;
    }
}
