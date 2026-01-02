<?php

namespace Modules\Customer\Enums;

class PersonalityTypeEnum
{
    const IMPATIENT = 1;
    const CALM = 2;
    const IMPULSIVE = 3;
    const METICULOUS = 4;
    const UNCLEAR = 5;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::IMPATIENT => 'Nóng nảy (quyết nhanh, dễ mất kiên nhẫn)',
            self::CALM => 'Điềm đạm (dễ chịu, không vội vàng)',
            self::IMPULSIVE => 'Bốc đồng (thích cảm xúc, dễ bị kích thích mua)',
            self::METICULOUS => 'Kỹ tính (hỏi chi tiết, so sánh nhiều)',
            self::UNCLEAR => 'Chưa rõ',
            default => '',
        };
    }

    public static function toArray(): array
    {
        return [
            self::IMPATIENT => self::getLabel(self::IMPATIENT),
            self::CALM => self::getLabel(self::CALM),
            self::IMPULSIVE => self::getLabel(self::IMPULSIVE),
            self::METICULOUS => self::getLabel(self::METICULOUS),
            self::UNCLEAR => self::getLabel(self::UNCLEAR),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::IMPATIENT,
            self::CALM,
            self::IMPULSIVE,
            self::METICULOUS,
            self::UNCLEAR,
        ];
    }
}
