<?php

namespace Modules\Customer\Enums;

enum CustomerTypeEnum: int
{
    const PERSONAL = 1;
    const COMPANY = 2;

    public static function getLabel($type)
    {
        return match ($type) {
            self::PERSONAL => 'Cá nhân',
            self::COMPANY => 'Công ty',
        };
    }
}
