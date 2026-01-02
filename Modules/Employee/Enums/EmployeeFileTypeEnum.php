<?php

namespace Modules\Employee\Enums;

enum EmployeeFileTypeEnum
{
    const AVATAR = 1;
    const ID_CARD_FRONT = 2;
    const ID_CARD_BACK = 3;
    const OTHER = 4;

    const AVATAR_LABEL = 'avatar';
    const ID_CARD_FRONT_LABEL = 'id_card_front';
    const ID_CARD_BACK_LABEL = 'id_card_back';
    const OTHER_LABEL = 'other';

    public static function getTypeByLabel($label): string
    {
        return match ($label) {
            self::AVATAR_LABEL => self::AVATAR,
            self::ID_CARD_FRONT_LABEL => self::ID_CARD_FRONT,
            self::ID_CARD_BACK_LABEL => self::ID_CARD_BACK,
            self::OTHER_LABEL => self::OTHER,
        };
    }
}
