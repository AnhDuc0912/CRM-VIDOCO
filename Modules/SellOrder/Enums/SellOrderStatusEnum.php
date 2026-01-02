<?php

namespace Modules\SellOrder\Enums;

enum SellOrderStatusEnum
{
    const CREATED = 1;
    const WAITING_FOR_APPROVAL = 2;
    const APPROVED = 3;
    const PROCESSING = 4;
    const COMPLETED = 5;
    const PAID = 6;
    const CANCELLED = 7;

    public static function getStatusName($status)
    {
        return match ($status) {
            self::CREATED => 'Đã tạo',
            self::WAITING_FOR_APPROVAL => 'Chờ duyệt',
            self::APPROVED => 'Đã duyệt',
            self::PROCESSING => 'Đang thực hiện',
            self::COMPLETED => 'Hoàn tất',
            self::PAID => 'Đã thanh toán',
            self::CANCELLED => 'Đã hủy',
        };
    }

    public static function getStatusOptions()
    {
        return [
            self::CREATED => 'Đã tạo',
            self::WAITING_FOR_APPROVAL => 'Chờ duyệt',
            self::APPROVED => 'Đã duyệt',
            self::PROCESSING => 'Đang thực hiện',
            self::COMPLETED => 'Hoàn tất',
            self::PAID => 'Đã thanh toán',
            self::CANCELLED => 'Đã hủy',
        ];
    }
}
