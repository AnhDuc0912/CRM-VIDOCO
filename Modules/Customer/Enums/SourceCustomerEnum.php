<?php

namespace Modules\Customer\Enums;

enum SourceCustomerEnum: int
{
    const FACEBOOK = 1;
    const WEBSITE = 2;
    const ZALO = 3;
    const WORK_SHOP = 4;
    const SELF_CONTACT = 5;
    const FRIEND = 6;

    public static function getLabel()
    {
        return [
            self::FACEBOOK => 'Facebook',
            self::WEBSITE => 'Website',
            self::ZALO => 'Zalo',
            self::WORK_SHOP => 'Workshop',
            self::SELF_CONTACT => 'Tự liên hệ',
            self::FRIEND => 'Bạn bè',
        ];
    }
}
