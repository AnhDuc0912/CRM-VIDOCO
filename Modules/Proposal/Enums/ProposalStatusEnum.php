<?php

namespace Modules\Proposal\Enums;

enum ProposalStatusEnum
{
    const NEW = 1;
    const NEGOTIATION = 2;
    const APPROVED = 3;
    const REJECTED = 4;
    const CONVER_TO_ORDER = 5;

    public static function getStatusName($status)
    {
        return match ($status) {
            self::NEW => 'Mới',
            self::NEGOTIATION => 'Đang thương lượng',
            self::APPROVED => 'Đã phê duyệt',
            self::REJECTED => 'Đã từ chối',
            self::CONVER_TO_ORDER => 'Đã chuyển thành đơn hàng',
        };
    }

    public static function getStatusOptions()
    {
        return [
            self::NEW => 'Mới',
            self::NEGOTIATION => 'Đang thương lượng',
            self::APPROVED => 'Đã phê duyệt',
            self::REJECTED => 'Đã từ chối',
            self::CONVER_TO_ORDER => 'Đã chuyển thành đơn hàng',
        ];
    }
}
