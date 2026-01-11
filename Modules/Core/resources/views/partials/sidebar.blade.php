@use('Modules\Core\Enums\RoleEnum')
@use('Modules\Core\Enums\PermissionEnum')
<!--sidebar-wrapper-->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="">
            <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon-2" alt="" />
        </div>
        <div>
            <h4 class="logo-text">VIDOCO</h4>
        </div>
        <a href="javascript:;" class="toggle-btn ms-auto"> <i class="bx bx-menu"></i>
        </a>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        @can(PermissionEnum::DASHBOARD_VIEW)
            <li class="menu-label">VIDO SYSTEM+</li>

            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon icon-color-1"><i class="bx bx-home-alt"></i>
                    </div>
                    <div class="menu-title">Thống Kê</div>
                </a>
                <ul>
                    <li> <a href="{{ route('dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Tổng Quan</a>
                    </li>
                    <li> <a href="{{ route('dashboard.business') }}"><i class="bx bx-right-arrow-alt"></i>Kinh doanh</a>
                    </li>
                </ul>
            </li>
        @endcan

        @can(PermissionEnum::EMPLOYEE_VIEW)
            <li class="menu-label">VIDO HRM+</li>

            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon icon-color-1"><i class="bx bx-group"></i>
                    </div>
                    <div class="menu-title">Hồ Sơ Nhân Sự</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('employees.create') }}">
                            <i class="bx bx-right-arrow-alt"></i>Thêm nhân sự
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employees.index') }}">
                            <i class="bx bx-right-arrow-alt"></i>Danh sách nhân sự
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('department.index') }}">
                            <i class="bx bx-right-arrow-alt"></i>Quản lý phòng ban
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('level.index') }}">
                            <i class="bx bx-right-arrow-alt"></i>Cấp bậc
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('position.index') }}">
                            <i class="bx bx-right-arrow-alt"></i>Vị trí
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon icon-color-9"><i class="bx bx-comment-edit"></i>
                    </div>
                    <div class="menu-title">Yêu Cầu - Đề Xuất</div>
                </a>
                @can(PermissionEnum::DAY_OFF_VIEW)
                    <ul>
                        <li> <a href="{{ route('dayoff.index') }}"><i class="bx bx-right-arrow-alt"></i>Đề xuất nghỉ phép
                            </a>
                        </li>
                    </ul>
                @endcan
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon icon-color-3">
                        <i class="bx bx-task"></i>
                    </div>
                    <div class="menu-title">Chấm Công</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('timekeeping.index') }}">
                            <i class="bx bx-right-arrow-alt"></i>Chấm công cá nhân
                        </a>
                    </li>

                    @can(PermissionEnum::DAY_OFF_VIEW_ALL)
                        <li>
                            <a href="{{ route('timekeeping.monthly') }}">
                                <i class="bx bx-right-arrow-alt"></i>Bảng chấm công
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>

        @endcan

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon icon-color-0">
                    <i class="bx bx-book-alt"></i>
                </div>
                <div class="menu-title">Văn Bản</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('document.create') }}"
                        class="{{ request()->routeIs('document.create') ? 'mm-active' : '' }}">
                        <i class="bx bx-right-arrow-alt"></i>Tạo văn bản
                    </a>
                </li>
                <li>
                    <a href="{{ route('document.index') }}"
                        class="{{ request()->routeIs('document.index') ? 'mm-active' : '' }}">
                        <i class="bx bx-right-arrow-alt"></i>Danh sách văn bản
                    </a>
                </li>

                <li>
                    <a href="{{ route('document.structure.index') }}"
                        class="{{ request()->routeIs('document.structure.*') ? 'mm-active' : '' }}">
                        <i class="bx bx-right-arrow-alt"></i>Cấu trúc văn bản
                    </a>
                </li>

            </ul>
        </li>




        <li class="menu-label">VIDO WORK+</li>
        @can(PermissionEnum::PROJECT_VIEW)
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon icon-color-1">
                        <i class="bx bx-archive"></i>
                    </div>
                    <div class="menu-title">Quản lý Dự Án</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('project.create') }}">
                            <i class="bx bx-right-arrow-alt"></i>Thêm dự án
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('project.index') }}">
                            <i class="bx bx-right-arrow-alt"></i>Danh sách dự án
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @can(PermissionEnum::WORK_VIEW)
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon icon-color-6">
                        <i class="bx bx-task"></i>
                    </div>
                    <div class="menu-title">Quản lý Công Việc</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('work.create') }}">
                            <i class="bx bx-right-arrow-alt"></i>Thêm công việc
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('work.index', ['filter' => 'my']) }}">
                            <i class="bx bx-right-arrow-alt"></i>Việc Của Tôi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('work.index', ['filter' => 'assign']) }}">
                            <i class="bx bx-right-arrow-alt"></i>Việc Tôi Giao
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('work.index', ['filter' => 'follow']) }}">
                            <i class="bx bx-right-arrow-alt"></i>Việc Theo Dõi
                        </a>
                    </li>
                </ul>

            </li>
            @can(PermissionEnum::WORK_UPDATE)
                <li>
                    <a href="{{ route('work.report.follow') }}">
                        <div class="parent-icon icon-color-7"><i class="bx bx-file-find"></i></div>
                        <div class="menu-title">Theo Dõi Báo Cáo</div>
                    </a>
                </li>
            @endcan
        @endcan
        @can(PermissionEnum::COMMENT_VIEW)
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon icon-color-1">
                        <i class="bx bx-conversation"></i>
                    </div>
                    <div class="menu-title">Quản lý Bình Luận</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('comment.index') }}">
                            <i class="bx bx-right-arrow-alt"></i>Danh sách bình luận
                        </a>
                    </li>
                </ul>
            </li>
        @endcan


        @canany([...PermissionEnum::getCategoryPermissions(), ...PermissionEnum::getCustomerPermissions()])
            <li class="menu-label">VIDO CRM+</li>
            @can(PermissionEnum::ORDER_VIEW)
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon icon-color-10"><i class="bx bx-file"></i>
                        </div>
                        <div class="menu-title">Dịch Vụ Khách Hàng</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('orders.active') }}">
                                <i class="bx bx-right-arrow-alt"></i>Dịch vụ đang dùng
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.expiring') }}">
                                <i class="bx bx-right-arrow-alt"></i>Dịch vụ sắp hết hạn
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.expired') }}">
                                <i class="bx bx-right-arrow-alt"></i>Dịch vụ hết hạn
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.create') }}">
                                <i class="bx bx-right-arrow-alt"></i>Thêm dịch vụ
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can(PermissionEnum::CUSTOMER_VIEW)
                <li>
                    <a href="{{ route('customers.index') }}"
                        class="{{ request()->routeIs('customers.*') ? 'mm-active' : '' }}">
                        <div class="parent-icon icon-color-4"><i class="bx bx-user-circle"></i>
                        </div>
                        <div class="menu-title">Hồ Sơ Khách Hàng</div>
                    </a>
                </li>
            @endcan
            <li>
                <a href="{{ route('customers.notification') }}" class="">
                    <div class="parent-icon icon-color-9"><i class="bx bx-envelope"></i>
                    </div> 
                    <div class="menu-title">Thông báo khách hàng</div>
                </a>
            </li>
            @canany([PermissionEnum::PROPOSAL_VIEW, PermissionEnum::SELL_CONTRACT_VIEW])
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon icon-color-7"><i class="bx bx-file"></i>
                        </div>
                        <div class="menu-title">Bán hàng</div>
                    </a>
                    <ul>
                        @can(PermissionEnum::PROPOSAL_VIEW)
                            <li> <a href="{{ route('proposals.index') }}"><i class="bx bx-right-arrow-alt"></i>Danh sách báo
                                    giá</a>
                            </li>
                        @endcan
                        @can(PermissionEnum::SELL_CONTRACT_VIEW)
                            <li> <a href="{{ route('sell-contracts.index') }}"><i class="bx bx-right-arrow-alt"></i>Danh sách hợp
                                    đồng </a>
                            </li>
                        @endcan
                        @can(PermissionEnum::SELL_ORDER_VIEW)
                            <li> <a href="{{ route('sell-orders.index') }}"><i class="bx bx-right-arrow-alt"></i>Danh sách đơn
                                    hàng
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @canany([PermissionEnum::CATEGORY_VIEW, PermissionEnum::SERVICE_VIEW])
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon icon-color-8"><i class="bx bx-diamond"></i>
                        </div>
                        <div class="menu-title">Dịch vụ bán ra</div>
                    </a>
                    <ul>
                        @can(PermissionEnum::CATEGORY_VIEW)
                            <li> <a href="{{ route('categories.index') }}"><i class="bx bx-right-arrow-alt"></i>Danh
                                    mục </a>
                            </li>
                        @endcan
                        @can(PermissionEnum::SERVICE_VIEW)
                            <li> <a href="{{ route('services.index') }}"><i class="bx bx-right-arrow-alt"></i>Dịch vụ</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            @can(PermissionEnum::AUTHORIZATION_VIEW)
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon icon-color-3"><i class="bx bx-lock"></i>
                        </div>
                        <div class="menu-title">Cấu hình chung</div>
                    </a>
                    <ul>
                        <li> <a href="{{ route('authorization') }}"><i class="bx bx-right-arrow-alt"></i>Phân quyền </a>
                        </li>
                         <li> <a href="{{ route('transfer-customers.form') }}"><i class="bx bx-right-arrow-alt"></i>Chuyển khách hàng</a>
                        </li>
                    </ul>
                </li>
            @endcan




        @endcanany
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar-wrapper-->
