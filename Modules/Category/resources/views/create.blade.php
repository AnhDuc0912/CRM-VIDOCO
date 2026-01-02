@extends('core::layouts.app')

@section('title', 'Thêm mới danh mục')

@section('content')
 <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản lý Danh Mục Dịch Vụ</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm Danh Mục Dịch Vụ</li>
                        </ol>
                    </nav>
                </div>
            </div>
    <div class="card radius-15">
        <div class="card-body">
            @include('category::components.form', [
                'action' => route('categories.store'),
                'method' => 'post',
            ])
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include Category Validation -->
    <script src="{{ asset('modules/category/js/category-validation.js') }}">
    </script>

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
    <script>
        $('#fancy-file-upload').FancyFileUpload({
            params: {
                action: 'fileuploader'
            },
            maxfilesize: 1000000,
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#image-uploadify').imageuploadify({
                multiple: true
            });
        })
    </script>
@endpush
