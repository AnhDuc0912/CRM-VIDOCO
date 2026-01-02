@extends('core::layouts.app')
@use('Modules\Core\Enums\PermissionEnum')

@section('title', 'Cập nhật khách hàng')

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Quản lý Khách Hàng</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cập Nhật Khách Hàng</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card radius-15">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#hosokhachhang"><span
                            class="p-tab-name">Thông tin cơ
                            bản</span><i class='bx bx-donate-blood font-24 d-sm-none'></i></a>
                </li>
                <li class="nav-item"> <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#hosochuyensau"><span
                            class="p-tab-name">Hành vi & Tâm lý</span><i
                            class='bx bxs-user-rectangle font-24 d-sm-none'></i></a>
                </li>
                <li class="nav-item"> <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#moiquanhe"><span
                            class="p-tab-name">Sở thích & Mối quan hệ</span><i
                            class='bx bxs-user-rectangle font-24 d-sm-none'></i></a>
                </li>
                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#upload_File"><span
                            class="p-tab-name">Upload
                            File</span><i class='bx bx-message-edit font-24 d-sm-none'></i></a>
                </li>
            </ul>

            <form action="{{ route('customers.update', $customer->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="hosokhachhang">
                        @include('customer::components.form-basic-info')
                    </div>
                    <div class="tab-pane fade" id="hosochuyensau">
                        @include('customer::components.form-behavior')
                    </div>
                    <div class="tab-pane fade" id="moiquanhe">
                        @include('customer::components.form-relationship')
                    </div>
                    <div class="tab-pane fade" id="upload_File">
                        @include('customer::components.form-upload-file')
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this)
                .hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this)
                .hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>
@endpush
