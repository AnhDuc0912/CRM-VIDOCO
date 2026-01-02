<?php

namespace Modules\Customer\Enums;

class SensitivePointEnum
{
    const HATES_PRICE_COMPARISON = 1;
    const HATES_TOO_MANY_CALLS = 2;
    const HATES_LONG_DELIVERY = 3;
    const HATES_BEING_FORCED = 4;
    const OTHER = 5;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::HATES_PRICE_COMPARISON => 'Ghét bị so sánh giá',
            self::HATES_TOO_MANY_CALLS => 'Ghét gọi điện quá nhiều',
            self::HATES_LONG_DELIVERY => 'Ghét chờ giao hàng lâu',
            self::HATES_BEING_FORCED => 'Không thích bị ép mua',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::HATES_PRICE_COMPARISON => self::getLabel(self::HATES_PRICE_COMPARISON),
            self::HATES_TOO_MANY_CALLS => self::getLabel(self::HATES_TOO_MANY_CALLS),
            self::HATES_LONG_DELIVERY => self::getLabel(self::HATES_LONG_DELIVERY),
            self::HATES_BEING_FORCED => self::getLabel(self::HATES_BEING_FORCED),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::HATES_PRICE_COMPARISON,
            self::HATES_TOO_MANY_CALLS,
            self::HATES_LONG_DELIVERY,
            self::HATES_BEING_FORCED,
        ];
    }
}
