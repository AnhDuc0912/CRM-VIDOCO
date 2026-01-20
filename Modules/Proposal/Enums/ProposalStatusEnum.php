<?php

namespace Modules\Proposal\Enums;

enum ProposalStatusEnum
{
    const NEW = 1;
    const NEGOTIATION = 2;
    const APPROVED = 3;
    const REJECTED = 4;
    const CONVER_TO_ORDER = 5;
    const REJECTED_REDO = 6;
    const CONVERT_TO_CONTRACT = 7;
    const REVISED = 8;

    public static function getStatusName($status)
    {
        return match ($status) {
            self::NEW => 'Mới',
            self::NEGOTIATION => 'Đang thương lượng',
            self::APPROVED => 'Đã phê duyệt',
            self::REJECTED => 'Đã từ chối',
            self::CONVER_TO_ORDER => 'Đã chuyển thành đơn hàng',
            self::REJECTED_REDO => 'Yêu cầu làm lại',
            self::CONVERT_TO_CONTRACT => 'Đã chuyển thành hợp đồng',
            self::REVISED => 'Đã chỉnh sửa',
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
            self::REJECTED_REDO => 'Yêu cầu làm lại',
            self::CONVERT_TO_CONTRACT => 'Đã chuyển thành hợp đồng',
            self::REVISED => 'Đã chỉnh sửa',
        ];
    }
}
