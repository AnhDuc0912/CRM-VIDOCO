<?php

namespace Modules\Customer\Enums;

enum SourceCustomerEnum: int
{
    const SELF_FIND = 1;           // Sale tự kiếm
    const MARKETING_CHANNEL = 2;   // Kênh Marketing
    const AGENT_1 = 3;             // CTV 1
    const AGENT_2 = 4;             // CTV 2
    const FACEBOOK = 5;
    const WEBSITE = 6;
    const ZALO = 7;
    const WORK_SHOP = 8;
    const SELF_CONTACT = 9;
    const FRIEND = 10;

    public static function getLabel()
    {
        return [
            self::SELF_FIND => 'Sale tự kiếm',
            self::MARKETING_CHANNEL => 'Kênh Marketing',
            self::AGENT_1 => 'CTV 1',
            self::AGENT_2 => 'CTV 2',
            self::FACEBOOK => 'Facebook',
            self::WEBSITE => 'Website',
            self::ZALO => 'Zalo',
            self::WORK_SHOP => 'Workshop',
            self::SELF_CONTACT => 'Tự liên hệ',
            self::FRIEND => 'Bạn bè',
        ];
    }
}
