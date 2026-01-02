<?php

namespace Modules\Core\Enums;

enum RelationshipEnum
{
    const FATHER = 1;
    const MOTHER = 2;
    const WIFE = 3;
    const HUSBAND = 4;
    const CHILD = 5;

    public static function getLabel($value)
    {
        return match ($value) {
            self::FATHER => 'Bố',
            self::MOTHER => 'Mẹ',
            self::WIFE => 'Vợ',
            self::HUSBAND => 'Chồng',
            self::CHILD => 'Con',
        };
    }
}
