@extends('core::layouts.app')

@section('title', 'Thêm Phòng Ban')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Phòng Ban</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm Phòng Ban</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Thông Tin Phòng Ban</h5>
                <form action="{{ route('department.store') }}" method="POST" class="row g-3 needs-validation" novalidate>
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Tên Phòng Ban</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Mô tả</label>
                        <textarea id="mytextarea" name="description"></textarea>
                    </div>

                    <div class="row g-3 mb-4 text-center">
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Lưu Dữ Liệu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/k85wu35kfr1wvk3csuts7oniwyqxsiavx137i8ls8rw4dbbs/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'lists link image code table',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | code'
        });
    </script>
@endpush
