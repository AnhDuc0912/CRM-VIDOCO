<?php

namespace Modules\Employee\Enums;

enum JobLevelEnum
{
    const LEADER = 1;
    const MANAGER = 2;
    const SPECIALIST = 3;
    const STAFF = 4;
    const INTERN = 5;

    public static function getLabel($level): string
    {
        return match ($level) {
            self::LEADER => 'Lãnh đạo',
            self::MANAGER => 'Quản lý',
            self::SPECIALIST => 'Chuyên viên',
            self::STAFF => 'Nhân viên',
            self::INTERN => 'Thực tập sinh',
        };
    }

    public static function getValues(): array
    {
        return [
            self::LEADER,
            self::MANAGER,
            self::SPECIALIST,
            self::STAFF,
            self::INTERN,
        ];
    }
}
