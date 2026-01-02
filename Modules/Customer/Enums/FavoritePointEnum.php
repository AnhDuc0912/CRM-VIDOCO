<?php

namespace Modules\Customer\Enums;

class FavoritePointEnum
{
    const LIKES_GIFTS = 1;
    const LIKES_PRAISE = 2;
    const LIKES_PRIORITY_SERVICE = 3;
    const LIKES_TALKING_FAMILY = 4;
    const UNCLEAR = 5;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::LIKES_GIFTS => 'Thích được tặng quà',
            self::LIKES_PRAISE => 'Thích được khen (sản phẩm/phong cách sống)',
            self::LIKES_PRIORITY_SERVICE => 'Thích được ưu tiên dịch vụ riêng',
            self::LIKES_TALKING_FAMILY => 'Thích nói về gia đình (con cái, người thân)',
            self::UNCLEAR => 'Chưa rõ',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::LIKES_GIFTS => self::getLabel(self::LIKES_GIFTS),
            self::LIKES_PRAISE => self::getLabel(self::LIKES_PRAISE),
            self::LIKES_PRIORITY_SERVICE => self::getLabel(self::LIKES_PRIORITY_SERVICE),
            self::LIKES_TALKING_FAMILY => self::getLabel(self::LIKES_TALKING_FAMILY),
            self::UNCLEAR => self::getLabel(self::UNCLEAR),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::LIKES_GIFTS,
            self::LIKES_PRAISE,
            self::LIKES_PRIORITY_SERVICE,
            self::LIKES_TALKING_FAMILY,
            self::UNCLEAR,
        ];
    }
}
