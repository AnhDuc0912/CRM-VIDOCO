<?php

namespace Modules\Category\Enums;

enum ServiceStatusEnum
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    const WAITING_FOR_APPROVAL = 2;
    const PENDING = 3;

    public static function getLabel($status)
    {
        return match ($status) {
            self::ACTIVE => 'Đang kinh doanh',
            self::INACTIVE => 'Ngừng kinh doanh',
            self::WAITING_FOR_APPROVAL => 'Chờ duyệt',
            self::PENDING => 'Tạm dừng',

            default => 'Không xác định',
        };
    }

    public static function getValues()
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::WAITING_FOR_APPROVAL,
            self::PENDING,
        ];
    }
}
