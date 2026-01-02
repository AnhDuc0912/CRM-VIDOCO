<?php

namespace Modules\Core\Enums;

enum GenderEnum
{
    const MALE = 1;
    const FEMALE = 2;
    const OTHER = 3;

    public static function getLabel()
    {
        return [
            self::MALE => 'Nam',
            self::FEMALE => 'Nữ',
            self::OTHER => 'Khác',
        ];
    }
}
