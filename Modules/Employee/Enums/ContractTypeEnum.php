<?php

namespace Modules\Employee\Enums;

class ContractTypeEnum
{
    const PROBATION = 1;
    const FIXED_TERM = 2;
    const UNLIMITED_TERM = 3;

    public static function getLabel($type): string
    {
        return match ($type) {
            self::PROBATION => 'Hợp đồng thử việc',
            self::FIXED_TERM => 'Hợp đồng có thời hạn',
            self::UNLIMITED_TERM => 'Hợp đồng không thời hạn',
        };
    }

    public static function getValues(): array
    {
        return [
            self::PROBATION,
            self::FIXED_TERM,
            self::UNLIMITED_TERM,
        ];
    }
}
