<?php

namespace Modules\Customer\Enums;

class PurchaseInfluencerEnum
{
    const SPOUSE = 1;
    const BOSS = 2;
    const FRIENDS_COLLEAGUES = 3;
    const SELF_DECISION = 4;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::SPOUSE => 'Vợ/Chồng',
            self::BOSS => 'Sếp/Cấp trên',
            self::FRIENDS_COLLEAGUES => 'Bạn bè/Đồng nghiệp',
            self::SELF_DECISION => 'Tự quyết định',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::SPOUSE => self::getLabel(self::SPOUSE),
            self::BOSS => self::getLabel(self::BOSS),
            self::FRIENDS_COLLEAGUES => self::getLabel(self::FRIENDS_COLLEAGUES),
            self::SELF_DECISION => self::getLabel(self::SELF_DECISION),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::SPOUSE,
            self::BOSS,
            self::FRIENDS_COLLEAGUES,
            self::SELF_DECISION,
        ];
    }
}
