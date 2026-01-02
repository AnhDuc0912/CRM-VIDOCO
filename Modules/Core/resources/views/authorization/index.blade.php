@extends('core::layouts.app')

@section('title', 'Phân quyền')

@section('content')
 <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý Phân Quyền</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh Sách Phân Quyền</li>
                        </ol>
                    </nav>
                </div>

            </div>
    <div class="card radius-15">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#employee"><span
                            class="p-tab-name">Phân quyền nhân viên</span><i
                            class='bx bx-donate-blood font-24 d-sm-none'></i></a>
                </li>
                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#department_tab"><span
                            class="p-tab-name">Phân quyền phòng ban</span><i
                            class='bx bxs-user-rectangle font-24 d-sm-none'></i></a>
                </li>
            </ul>
            <div class="tab-content mt-3">
                @include('core::authorization.components.employee')
                @include('core::authorization.components.department')
            </div>
        </div>
    </div>

@endsection
