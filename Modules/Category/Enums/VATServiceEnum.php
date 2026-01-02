<?php

namespace Modules\Category\Enums;

enum VATServiceEnum
{
    const ZERO = 0;
    const FIVE = 5;
    const EIGHT = 8;
    const TEN = 10;

    public static function getLabel($status)
    {
        return match ($status) {
            self::ZERO => '0%',
            self::FIVE => '5%',
            self::EIGHT => '8%',
            self::TEN => '10%',

            default => 'Không có thuế',
        };
    }

    public static function getValues()
    {
        return [
            self::ZERO,
            self::FIVE,
            self::EIGHT,
            self::TEN,
        ];
    }
}
