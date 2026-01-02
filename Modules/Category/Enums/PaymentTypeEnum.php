<?php

namespace Modules\Category\Enums;

enum PaymentTypeEnum
{
    const RENEWABLE = 1;
    const NON_RENEWABLE = 2;

    public static function getLabel($type)
    {
        return match ($type) {
            self::RENEWABLE => 'Gia hạn',
            self::NON_RENEWABLE => 'Không gia hạn',
        };
    }

    public static function getValues()
    {
        return [
            self::RENEWABLE,
            self::NON_RENEWABLE,
        ];
    }
}
