<?php

namespace Modules\Customer\Enums;

class CommonEmotionEnum
{
    const HAPPY = 1;
    const NORMAL = 2;
    const ANNOYED = 3;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::HAPPY => 'Vui vẻ',
            self::NORMAL => 'Bình thường',
            self::ANNOYED => 'Khó chịu',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::HAPPY => self::getLabel(self::HAPPY),
            self::NORMAL => self::getLabel(self::NORMAL),
            self::ANNOYED => self::getLabel(self::ANNOYED),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::HAPPY,
            self::NORMAL,
            self::ANNOYED,
        ];
    }
}
