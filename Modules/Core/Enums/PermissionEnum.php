<?php

namespace Modules\Core\Enums;

enum PermissionEnum: string
{
    // Dashboard
    const DASHBOARD_VIEW = 'dashboard.view';

    // Authorization
    const AUTHORIZATION_VIEW = 'authorization.view';
    const AUTHORIZATION_UPDATE = 'authorization.update';

    // Employee
    const EMPLOYEE_VIEW = 'employee.view';
    const EMPLOYEE_SHOW = 'employee.show';
    const EMPLOYEE_CREATE = 'employee.create';
    const EMPLOYEE_UPDATE = 'employee.update';
    const EMPLOYEE_DELETE = 'employee.delete';
    const EMPLOYEE_UPDATE_STATUS = 'employee.update_status';

    // Category
    const CATEGORY_VIEW = 'category.view';
    const CATEGORY_CREATE = 'category.create';
    const CATEGORY_UPDATE = 'category.update';
    const CATEGORY_DELETE = 'category.delete';
    const CATEGORY_SHOW = 'category.show';
    const CATEGORY_DOWNLOAD_FILES = 'category.download_files';
    const CATEGORY_DELETE_FILES = 'category.delete_files';

    // Service
    const SERVICE_VIEW = 'service.view';
    const SERVICE_CREATE = 'service.create';
    const SERVICE_UPDATE = 'service.update';
    const SERVICE_DELETE = 'service.delete';

    // Customer
    const CUSTOMER_VIEW = 'customer.view';
    const CUSTOMER_CREATE = 'customer.create';
    const CUSTOMER_UPDATE = 'customer.update';
    const CUSTOMER_DELETE = 'customer.delete';
    const CUSTOMER_SHOW = 'customer.show';
    const CUSTOMER_SHOW_ALL = 'customer.show_all';
    const CUSTOMER_INCHARGE = 'customer.incharge';

    // Order
    const ORDER_VIEW = 'order.view';
    const ORDER_CREATE = 'order.create';
    const ORDER_UPDATE = 'order.update';
    const ORDER_DELETE = 'order.delete';
    const ORDER_SHOW = 'order.show';

    // Proposal
    const PROPOSAL_VIEW = 'proposal.view';
    const PROPOSAL_CREATE = 'proposal.create';
    const PROPOSAL_UPDATE = 'proposal.update';
    const PROPOSAL_DELETE = 'proposal.delete';
    const PROPOSAL_SHOW = 'proposal.show';
    const PROPOSAL_DOWNLOAD_FILES = 'proposal.download_files';
    const PROPOSAL_CONVERT_TO_ORDER = 'proposal.convert_to_order';

    // Sell Contract
    const SELL_CONTRACT_VIEW = 'sell_contract.view';
    const SELL_CONTRACT_CREATE = 'sell_contract.create';
    const SELL_CONTRACT_UPDATE = 'sell_contract.update';
    const SELL_CONTRACT_DELETE = 'sell_contract.delete';
    const SELL_CONTRACT_SHOW = 'sell_contract.show';
    const SELL_CONTRACT_DOWNLOAD_FILES = 'sell_contract.download_files';
    const SELL_CONTRACT_CONVERT_TO_ORDER = 'sell_contract.convert_to_order';

    // Sell Order
    const SELL_ORDER_VIEW = 'sell_order.view';
    const SELL_ORDER_CREATE = 'sell_order.create';
    const SELL_ORDER_UPDATE = 'sell_order.update';
    const SELL_ORDER_DELETE = 'sell_order.delete';
    const SELL_ORDER_SHOW = 'sell_order.show';
    const SELL_ORDER_DOWNLOAD_FILES = 'sell_order.download_files';

    // Project
    const PROJECT_VIEW = 'project.view';
    const PROJECT_SHOW = 'project.show';
    const PROJECT_CREATE = 'project.create';
    const PROJECT_UPDATE = 'project.update';
    const PROJECT_DELETE = 'project.delete';

    // Work
    const WORK_VIEW = 'work.view';
    const WORK_SHOW = 'work.show';
    const WORK_CREATE = 'work.create';
    const WORK_UPDATE = 'work.update';
    const WORK_DELETE = 'work.delete';

    // Comment
    const COMMENT_VIEW = 'comment.view';
    const COMMENT_SHOW = 'comment.show';
    const COMMENT_CREATE = 'comment.create';
    const COMMENT_UPDATE = 'comment.update';
    const COMMENT_DELETE = 'comment.delete';

    // Day Off (Lịch nghỉ phép)
    const DAY_OFF_VIEW = 'day_off.view';
    const DAY_OFF_SHOW = 'day_off.show';
    const DAY_OFF_CREATE = 'day_off.create';
    const DAY_OFF_UPDATE = 'day_off.update';
    const DAY_OFF_DELETE = 'day_off.delete';
    const DAY_OFF_APPROVE = 'day_off.approve';
    const DAY_OFF_VIEW_ALL = 'day_off.view_all';

    // Resource label map
    const RESOURCE_LABEL_MAP = [
        'employee' => 'Nhân viên',
        'category' => 'Danh mục',
        'service' => 'Dịch vụ',
        'authorization' => 'Phân quyền',
        'dashboard' => 'Bảng điều khiển',
        'customer' => 'Khách hàng',
        'order' => 'Đơn hàng',
        'proposal' => 'Báo giá bán hàng',
        'sell_contract' => 'Hợp đồng bán hàng',
        'sell_order' => 'Đơn hàng bán hàng',
        'project' => 'Dự án',
        'work' => 'Công việc',
        'comment' => 'Bình luận',
        'day_off' => 'Lịch nghỉ phép/ Chấm Công',
    ];

    // Action label map
    const ACTION_LABEL_MAP = [
        'view' => 'Xem danh sách',
        'create' => 'Tạo mới',
        'update' => 'Chỉnh sửa',
        'delete' => 'Xóa',
        'download_files' => 'Tải file',
        'delete_files' => 'Xóa file',
        'show' => 'Xem chi tiết',
        'show_all' => 'Xem chi tiết tất cả',
        'incharge' => 'Phụ trách',
        'update_status' => 'Cập nhật trạng thái',
        'convert_to_order' => 'Chuyển thành đơn hàng',
        'approve' => 'Duyệt nghỉ phép',
        'view_all' => 'Xem hết',
    ];

    // Employee
    public static function getEmployeePermissions()
    {
        return [
            self::EMPLOYEE_VIEW,
            self::EMPLOYEE_SHOW,
            self::EMPLOYEE_CREATE,
            self::EMPLOYEE_UPDATE,
            self::EMPLOYEE_DELETE,
            self::EMPLOYEE_UPDATE_STATUS,
        ];
    }

    // Category
    public static function getCategoryPermissions()
    {
        return [
            self::CATEGORY_VIEW,
            self::CATEGORY_CREATE,
            self::CATEGORY_UPDATE,
            self::CATEGORY_DELETE,
            self::CATEGORY_DOWNLOAD_FILES,
            self::CATEGORY_DELETE_FILES,
            self::CATEGORY_SHOW,
        ];
    }

    // Service
    public static function getServicePermissions()
    {
        return [
            self::SERVICE_VIEW,
            self::SERVICE_CREATE,
            self::SERVICE_UPDATE,
            self::SERVICE_DELETE,
        ];
    }

    // Customer
    public static function getCustomerPermissions()
    {
        return [
            self::CUSTOMER_VIEW,
            self::CUSTOMER_SHOW,
            self::CUSTOMER_SHOW_ALL,
            self::CUSTOMER_INCHARGE,
            self::CUSTOMER_CREATE,
            self::CUSTOMER_UPDATE,
            self::CUSTOMER_DELETE,
        ];
    }

    // Order
    public static function getOrderPermissions()
    {
        return [
            self::ORDER_VIEW,
            self::ORDER_CREATE,
            self::ORDER_UPDATE,
            self::ORDER_DELETE,
            self::ORDER_SHOW,
        ];
    }

    // Proposal
    public static function getProposalPermissions()
    {
        return [
            self::PROPOSAL_VIEW,
            self::PROPOSAL_CREATE,
            self::PROPOSAL_UPDATE,
            self::PROPOSAL_DELETE,
            self::PROPOSAL_SHOW,
            self::PROPOSAL_DOWNLOAD_FILES,
            self::PROPOSAL_CONVERT_TO_ORDER,
        ];
    }

    // Sell Contract
    public static function getSellContractPermissions()
    {
        return [
            self::SELL_CONTRACT_VIEW,
            self::SELL_CONTRACT_CREATE,
            self::SELL_CONTRACT_UPDATE,
            self::SELL_CONTRACT_DELETE,
            self::SELL_CONTRACT_SHOW,
            self::SELL_CONTRACT_DOWNLOAD_FILES,
            self::SELL_CONTRACT_CONVERT_TO_ORDER,
        ];
    }

    // Sell Order
    public static function getSellOrderPermissions()
    {
        return [
            self::SELL_ORDER_VIEW,
            self::SELL_ORDER_CREATE,
            self::SELL_ORDER_UPDATE,
            self::SELL_ORDER_DELETE,
            self::SELL_ORDER_SHOW,
            self::SELL_ORDER_DOWNLOAD_FILES,
        ];
    }

    // Day Off
    public static function getDayOffPermissions()
    {
        return [
            self::DAY_OFF_VIEW,
            self::DAY_OFF_SHOW,
            self::DAY_OFF_CREATE,
            self::DAY_OFF_UPDATE,
            self::DAY_OFF_DELETE,
            self::DAY_OFF_APPROVE,
            self::DAY_OFF_VIEW_ALL,
        ];
    }

    // Project
    public static function getProjectPermissions()
    {
        return [
            self::PROJECT_VIEW,
            self::PROJECT_SHOW,
            self::PROJECT_CREATE,
            self::PROJECT_UPDATE,
            self::PROJECT_DELETE,
        ];
    }

    // Work
    public static function getWorkPermissions()
    {
        return [
            self::WORK_VIEW,
            self::WORK_SHOW,
            self::WORK_CREATE,
            self::WORK_UPDATE,
            self::WORK_DELETE,
        ];
    }

    // Comment
    public static function getCommentPermissions()
    {
        return [
            self::COMMENT_VIEW,
            self::COMMENT_SHOW,
            self::COMMENT_CREATE,
            self::COMMENT_UPDATE,
            self::COMMENT_DELETE,
        ];
    }
}
