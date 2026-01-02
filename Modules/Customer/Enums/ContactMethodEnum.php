<?php

namespace Modules\Customer\Enums;

class ContactMethodEnum
{
    const DIRECT_CALL = 1;
    const MESSAGE_FIRST = 2;
    const ZALO_ONLY = 3;
    const FACEBOOK_ONLY = 4;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::DIRECT_CALL => 'Gọi trực tiếp',
            self::MESSAGE_FIRST => 'Nhắn tin trước',
            self::ZALO_ONLY => 'Chỉ Zalo',
            self::FACEBOOK_ONLY => 'Chỉ Facebook',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::DIRECT_CALL => self::getLabel(self::DIRECT_CALL),
            self::MESSAGE_FIRST => self::getLabel(self::MESSAGE_FIRST),
            self::ZALO_ONLY => self::getLabel(self::ZALO_ONLY),
            self::FACEBOOK_ONLY => self::getLabel(self::FACEBOOK_ONLY),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::DIRECT_CALL,
            self::MESSAGE_FIRST,
            self::ZALO_ONLY,
            self::FACEBOOK_ONLY,
        ];
    }
}
