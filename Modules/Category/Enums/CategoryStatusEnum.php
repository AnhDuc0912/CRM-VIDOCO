<?php

namespace Modules\Category\Enums;

enum CategoryStatusEnum
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    const PENDING = 2;
    const SUSPENDED = 3;

    public static function getLabel($status)
    {
        return match ($status) {
            self::ACTIVE => 'Hiệu lực',
            self::INACTIVE => 'Ngừng kinh doanh',
            self::PENDING => 'Chờ Duyệt',
            self::SUSPENDED => 'Tạm ngừng',
        };
    }

    public static function getValues()
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::PENDING,
            self::SUSPENDED,
        ];
    }
}
