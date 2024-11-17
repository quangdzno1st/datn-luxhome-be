<?php

namespace App\Constant\Enum;

enum StatusOrderEnum: int
{
    case CHUA_THANH_TOAN = 1;
    case DA_THANH_TOAN = 2;
    case THANH_TOAN_KET_THUC = 3;

    public function getName(): string
    {
        return match($this) {
            self::CHUA_THANH_TOAN => 'Chưa thanh toán',
            self::DA_THANH_TOAN => 'Đã thanh toán',
            self::THANH_TOAN_KET_THUC => 'Thanh toán kết thúc',
        };
    }

    public static function parse(int $value): ?self
    {
        return self::tryFrom($value);
    }

    public static function isChuaThanhToan($value): bool
    {
        return self::tryFrom($value) === self::CHUA_THANH_TOAN;
    }
}
