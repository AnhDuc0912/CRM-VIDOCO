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
                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#transfer_tab"><span
                            class="p-tab-name">Chuyển khách hàng</span><i
                            class='bx bx-transfer-alt font-24 d-sm-none'></i></a>
                </li>
            </ul>
            <div class="tab-content mt-3">
                @include('core::authorization.components.employee')
                @include('core::authorization.components.department')
                
                <div id="transfer_tab" class="tab-pane fade">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Chuyển khách hàng giữa nhân viên</h5>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Lỗi!</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('transfer-customers.process') }}" method="POST">
                                @csrf

                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-lg-6">
                                        <label for="from_employee_id" class="form-label">Chọn nhân viên cần chuyển <span class="text-danger">*</span></label>
                                        <select name="from_employee_id" id="from_employee_id" class="form-select select2-single" required>
                                            <option value="">-- Chọn nhân viên --</option>
                                            @foreach ($salesPersons as $employee)
                                                <option value="{{ $employee->id }}" {{ old('from_employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }} ({{ $employee->position->name ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('from_employee_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <label for="to_employee_id" class="form-label">Chuyển sang nhân viên <span class="text-danger">*</span></label>
                                        <select name="to_employee_id" id="to_employee_id" class="form-select select2-single" required>
                                            <option value="">-- Chọn nhân viên --</option>
                                            @foreach ($salesPersons as $employee)
                                                <option value="{{ $employee->id }}" {{ old('to_employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }} ({{ $employee->position->name ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('to_employee_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Loại chuyển <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="transfer_type" id="transfer_sales" value="sales_person" 
                                                {{ old('transfer_type', 'sales_person') == 'sales_person' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="transfer_sales">
                                                Kinh doanh (Nhân viên kinh doanh)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="transfer_type" id="transfer_incharge" value="person_incharge" 
                                                {{ old('transfer_type') == 'person_incharge' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="transfer_incharge">
                                                CSKH (Người phụ trách)
                                            </label>
                                        </div>
                                        @error('transfer_type')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="alert alert-warning" role="alert">
                                    <strong><i class="bx bx-info-circle"></i> Lưu ý:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Tất cả khách hàng của nhân viên nguồn sẽ được chuyển sang nhân viên đích</li>
                                        <li>Hành động này không thể hoàn tác, hãy chắc chắn trước khi tiếp hành</li>
                                        <li>Chỉ các nhân viên ở vị trí "Kinh Doanh" mới có thể được chọn</li>
                                    </ul>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc chắn muốn chuyển khách hàng? Hành động này không thể hoàn tác!');">
                                        <i class="bx bx-transfer-alt"></i> Chuyển khách hàng
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
