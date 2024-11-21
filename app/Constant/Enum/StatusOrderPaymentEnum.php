<?php

namespace App\Constant\Enum;

enum StatusOrderPaymentEnum: int
{
    case CHUA_THANH_TOAN = 1;
    case DA_THANH_TOAN = 2;
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

    public static function isDangCho($value): bool
    {
        return self::tryFrom($value) === self::DANG_CHO;
    }

    public static function isDaXacNhan($value): bool
    {
        return self::tryFrom($value) === self::DA_XAC_NHAN;
    }
}
