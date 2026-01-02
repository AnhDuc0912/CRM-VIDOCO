<?php

namespace Modules\Customer\Enums;

class SalutationEnum
{
    const ANH = 1;
    const CHI = 2;
    const BAC = 3;
    const EM = 4;
    const BY_NAME = 5;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::ANH => 'Anh',
            self::CHI => 'Chị',
            self::BAC => 'Bác',
            self::EM => 'Em',
            self::BY_NAME => 'Xưng tên',
            default => '',
        };
    }

    public static function getLabels(): array
    {
        return [
            self::ANH => 'Anh',
            self::CHI => 'Chị',
            self::BAC => 'Bác',
            self::EM => 'Em',
            self::BY_NAME => 'Xưng tên'
        ];
    }

    public static function toArray(): array
    {
        return [
            self::ANH => self::getLabel(self::ANH),
            self::CHI => self::getLabel(self::CHI),
            self::BAC => self::getLabel(self::BAC),
            self::EM => self::getLabel(self::EM),
            self::BY_NAME => self::getLabel(self::BY_NAME),
        ];
    }

    public static function getValues(): array
    {
        return [
            self::ANH,
            self::CHI,
            self::BAC,
            self::EM,
            self::BY_NAME,
        ];
    }

    public static function getLabelByValue(int $value): string
    {
        return self::getLabel($value);
    }
}
