<?php

namespace Modules\Customer\Enums;

class PurchaseDecisionTimeEnum
{
    const FAST = 1;
    const AVERAGE = 2;
    const SLOW = 3;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::FAST => 'Nhanh (1-2 lần trao đổi)',
            self::AVERAGE => 'Trung bình (3-5 lần)',
            self::SLOW => 'Chậm (cần tư vấn lâu, >5 lần)',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::FAST => self::getLabel(self::FAST),
            self::AVERAGE => self::getLabel(self::AVERAGE),
            self::SLOW => self::getLabel(self::SLOW),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::FAST,
            self::AVERAGE,
            self::SLOW,
        ];
    }
}
