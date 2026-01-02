<?php

namespace Modules\Category\Enums;

enum PaymentPeriodEnum
{
    const YEAR = 1;
    const MONTH = 2;
    const PACK = 3;
    const TOOL = 4;
    const POST = 5;

    public static function getLabel($status)
    {
        return match ($status) {
            self::YEAR => 'Năm',
            self::MONTH => 'Tháng',
            self::PACK => 'Gói',
            self::TOOL => 'Ấn Phẩm',
            self::POST => 'Bài Viết',
        };
    }

    public static function getValues()
    {
        return [
            self::YEAR,
            self::MONTH,
            self::PACK,
            self::TOOL,
            self::POST,
        ];
    }
}
