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

    public function getStatusColor(): string
    {
        return match ($this) {
            self::DANG_CHO => '#575145',        // Màu cho "Đang chờ"
            self::DA_XAC_NHAN => '#2196F3',     // Màu cho "Đã xác nhận"
            self::HOAN_THANH => 'green',      // Màu cho "Hoàn thành"
            self::DA_HUY => '#F44336',          // Màu cho "Đã hủy"
            self::YEU_CAU_HUY => '#FF9800',     // Màu cho "Yêu cầu hủy"
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

    public static function isHoanThanh($value): bool
    {
        return self::tryFrom($value) === self::HOAN_THANH;
    }

    public static function isYeuCauHuy($value): bool
    {
        return self::tryFrom($value) === self::YEU_CAU_HUY;
    }

    public static function isDaHuy($value): bool
    {
        return self::tryFrom($value) === self::DA_HUY;
    }
}

