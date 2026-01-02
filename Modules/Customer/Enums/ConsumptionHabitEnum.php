<?php

namespace Modules\Customer\Enums;

class ConsumptionHabitEnum
{
    const LIKES_PROMOTIONS = 1;
    const LIKES_BARGAINING = 2;
    const ONLY_HIGH_END = 3;
    const LIKES_TO_TRY = 4;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::LIKES_PROMOTIONS => 'Thích khuyến mãi',
            self::LIKES_BARGAINING => 'Thích trả giá',
            self::ONLY_HIGH_END => 'Chỉ mua hàng cao cấp',
            self::LIKES_TO_TRY => 'Thích dùng thử trước',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::LIKES_PROMOTIONS => self::getLabel(self::LIKES_PROMOTIONS),
            self::LIKES_BARGAINING => self::getLabel(self::LIKES_BARGAINING),
            self::ONLY_HIGH_END => self::getLabel(self::ONLY_HIGH_END),
            self::LIKES_TO_TRY => self::getLabel(self::LIKES_TO_TRY),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::LIKES_PROMOTIONS,
            self::LIKES_BARGAINING,
            self::ONLY_HIGH_END,
            self::LIKES_TO_TRY,
        ];
    }
}
