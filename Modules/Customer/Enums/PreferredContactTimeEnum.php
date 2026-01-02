<?php

namespace Modules\Customer\Enums;

class PreferredContactTimeEnum
{
    const MORNING = 1;
    const NOON = 2;
    const AFTERNOON = 3;
    const EVENING = 4;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::MORNING => 'Sáng',
            self::NOON => 'Trưa',
            self::AFTERNOON => 'Chiều',
            self::EVENING => 'Tối',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::MORNING => self::getLabel(self::MORNING),
            self::NOON => self::getLabel(self::NOON),
            self::AFTERNOON => self::getLabel(self::AFTERNOON),
            self::EVENING => self::getLabel(self::EVENING),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::MORNING,
            self::NOON,
            self::AFTERNOON,
            self::EVENING,
        ];
    }
}
