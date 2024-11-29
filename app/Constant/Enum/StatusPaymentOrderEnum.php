<?php

namespace App\Constant\Enum;

enum StatusPaymentOrderEnum: int
{
    case CHUA_THANH_TOAN = 1;
    case  DA_THANH_TOAN = 2;
    case DA_HOAN_TIEN = 3;
    case CHUA_HOAN_TIEN = 4;

    public function getName(): string
    {
        return match ($this) {
            self::CHUA_THANH_TOAN => 'Chưa thanh toán',
            self::DA_THANH_TOAN => 'Đã thanh toán',
            self::DA_HOAN_TIEN => 'Đã hoàn tiền',
            self::CHUA_HOAN_TIEN => 'Chưa hoàn tiền',
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

    public static function isDaThanhToan($value): bool
    {
        return self::tryFrom($value) === self::DA_THANH_TOAN;
    }

    public static function isChuaHoanTien($value): bool
    {
        return self::tryFrom($value) === self::CHUA_HOAN_TIEN;
    }

    public static function isDaHoanTien($value): bool
    {
        return self::tryFrom($value) === self::DA_HOAN_TIEN;
    }
}
