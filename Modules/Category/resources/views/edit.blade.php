@extends('core::layouts.app')

@section('title', 'Cập nhật danh mục')

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Quản lý Danh Mục Dịch Vụ</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh Sửa Danh Mục Dịch Vụ</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card radius-15">
        <div class="card-body">
            @include('category::components.form', [
                'action' => route('categories.update', $category->id),
                'method' => 'put',
                'category' => $category,
            ])
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include Category Validation -->
    <script src="{{ asset('modules/category/js/category-validation.js') }}"></script>

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
            maxfilesize: 1000000
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#submit-btn').on('click', function() {});
            $('#image-uploadify').imageuploadify();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.delete-file', function() {
            var fileId = $(this).data('id');
            var fileContainer = $('#file-' + fileId);

            if (confirm('Bạn có chắc chắn muốn xóa file này?')) {
                $.ajax({
                    url: "{{ route('categories.delete-file', ':fileId') }}"
                        .replace(':fileId', fileId),
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Lobibox.notify('success', {
                                title: 'Thành công',
                                msg: response.message,
                                position: 'top right',
                                delay: 5000,
                                sound: false,
                                width: 350,
                                showClass: 'fadeInDown',
                                hideClass: 'fadeOutUp',
                                icon: 'bx bx-check-circle',
                                rounded: true,
                                class: 'my-lobibox-toast'
                            });
                            fileContainer.remove();
                        } else {
                            Lobibox.notify('error', {
                                title: 'Lỗi',
                                msg: response.message,
                                position: 'top right',
                                delay: 5000,
                                sound: false,
                                width: 350,
                                showClass: 'fadeInDown',
                                hideClass: 'fadeOutUp',
                                icon: 'bx bx-error',
                                rounded: true,
                                class: 'my-lobibox-toast'
                            });
                        }
                    },
                    error: function(xhr, status, error) {}
                });
            }
        });
    </script>
@endpush
