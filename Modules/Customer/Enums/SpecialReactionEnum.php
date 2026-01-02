<?php

namespace Modules\Customer\Enums;

class SpecialReactionEnum
{
    const HAS_BEEN_ANGRY = 1;
    const REQUESTED_STRONG_DISCOUNT = 2;
    const COMPLAINED_QUALITY = 3;
    const PRAISED_SERVICE = 4;
    const OTHER = 5;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::HAS_BEEN_ANGRY => 'Từng nổi giận',
            self::REQUESTED_STRONG_DISCOUNT => 'Từng yêu cầu giảm giá mạnh',
            self::COMPLAINED_QUALITY => 'Từng than phiền chất lượng',
            self::PRAISED_SERVICE => 'Từng khen ngợi dịch vụ',
            self::OTHER => 'Khác',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::HAS_BEEN_ANGRY => self::getLabel(self::HAS_BEEN_ANGRY),
            self::REQUESTED_STRONG_DISCOUNT => self::getLabel(self::REQUESTED_STRONG_DISCOUNT),
            self::COMPLAINED_QUALITY => self::getLabel(self::COMPLAINED_QUALITY),
            self::PRAISED_SERVICE => self::getLabel(self::PRAISED_SERVICE),
            self::OTHER => self::getLabel(self::OTHER),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::HAS_BEEN_ANGRY,
            self::REQUESTED_STRONG_DISCOUNT,
            self::COMPLAINED_QUALITY,
            self::PRAISED_SERVICE,
            self::OTHER,
        ];
    }
}
