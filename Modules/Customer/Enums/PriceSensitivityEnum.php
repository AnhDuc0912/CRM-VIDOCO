<?php

namespace Modules\Customer\Enums;

class PriceSensitivityEnum
{
    const VERY_SENSITIVE = 1;
    const MODERATE = 2;
    const NOT_IMPORTANT = 3;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::VERY_SENSITIVE => 'Rất nhạy cảm (chỉ mua khi giảm giá)',
            self::MODERATE => 'Vừa phải (có thể chi thêm nếu thuyết phục được)',
            self::NOT_IMPORTANT => 'Không quan trọng giá (ưu tiên chất lượng/dịch vụ)',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::VERY_SENSITIVE => self::getLabel(self::VERY_SENSITIVE),
            self::MODERATE => self::getLabel(self::MODERATE),
            self::NOT_IMPORTANT => self::getLabel(self::NOT_IMPORTANT),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::VERY_SENSITIVE,
            self::MODERATE,
            self::NOT_IMPORTANT,
        ];
    }
}
