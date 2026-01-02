<div class="tab-pane fade" id="department_tab" role="tabpanel">
    <form action="{{ route('updatePermissionsDepartment') }}" method="POST" id="departmentPermissionsForm">
        @csrf
        @method('PUT')

        <!-- Select phòng ban -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="department_select" class="h5">Chọn phòng ban:</label>
                <select class="form-control select2" id="department_select" name="department_id" style="width: 100%;">
                    <option value="">-- Chọn phòng ban --</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Module Filter -->
            <div class="col-md-6">
                <label for="department_module_filter" class="h5">
                    <i class="fas fa-filter me-2"></i>Lọc theo danh mục:
                </label>
                <select class="form-control select2" id="department_module_filter" multiple="multiple"
                    style="width: 100%;">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($grouped as $moduleName => $permissions)
                        <option value="{{ $moduleName }}"
                            {{ in_array($moduleName, $selectedModules ?? []) ? 'selected' : '' }}>
                            {{ $moduleName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Bảng quyền cho phòng ban -->
        <div id="department_permissions_table" class="d-none">
            <div class="table-responsive">
                <table class="table table-bordered" id="department_permissions_table">
                    <tr class="bg-light">
                        <th width="30%">Màn hình</th>
                        <th width="50%">Quyền</th>
                        <th class="text-center" width="10%">
                            <input type="checkbox" class="form-check-input" id="select_all_permissions_department">
                        </th>
                    </tr>
                    <tbody id="department_permissions_table_body">
                        @foreach ($grouped as $moduleName => $permissions)
                            @foreach ($permissions as $permission => $label)
                                <tr class="department-permission-row" data-module="{{ $moduleName }}"
                                    data-permission="{{ $permission }}" style="border-color: #d6d5d5;">
                                    @if ($loop->first)
                                        <td rowspan="{{ count($permissions) }}"
                                            class="align-middle text-dark bg-light font-weight-bold h4 department-module-cell"
                                            data-module="{{ $moduleName }}">
                                            <i class="fas fa-cog mr-2"></i>{{ $moduleName }}
                                        </td>
                                    @endif
                                    <td>
                                        <span class="text-dark font-weight-bold h6">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input department-permission-checkbox"
                                            name="permissions[]" value="{{ $permission }}"
                                            data-permission="{{ $permission }}">
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Thông tin số lượng -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Đang hiển thị: <span id="department_visible_modules_count">{{ count($grouped) }}</span> danh
                        mục
                        | <span id="department_visible_permissions_count">
                            {{ collect($grouped)->sum(function ($permissions) {return count($permissions);}) }}
                        </span> quyền
                    </small>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu quyền phòng ban
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Khởi tạo select2 cho department select
            $('#department_select').select2({
                theme: 'bootstrap4',
                placeholder: '-- Chọn phòng ban --',
                width: '100%',
                language: {
                    noResults: function() {
                        return "Không tìm thấy kết quả";
                    },
                    searching: function() {
                        return "Đang tìm kiếm...";
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
            }).on('select2:open', function() {
                setTimeout(function() {
                    $('.select2-search__field').focus();
                }, 100);
            }).on('select2:select', function(e) {
                const departmentId = $(this).val();

                if (departmentId) {
                    // Hiển thị bảng permissions
                    $('#department_permissions_table').removeClass('d-none');

                    // Gọi API để lấy permissions của phòng ban
                    $.ajax({
                        url: `/departments/${departmentId}/permissions`,
                        type: 'GET',
                        success: function(response) {

                        },
                        error: function() {
                            Lobibox.notify('error', {
                                title: 'Lỗi',
                                msg: '{{ $errors->any() ? $errors->first() : session('error') }}',
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
                    });
                } else {
                    $('#department_permissions_table').addClass('d-none');
                }
            });

            // Khởi tạo select2 cho department module filter
            $('#department_module_filter').select2({
                theme: 'bootstrap4',
                placeholder: '-- Chọn danh mục --',
                width: '100%',
                closeOnSelect: false,
                language: {
                    noResults: function() {
                        return "Không tìm thấy danh mục";
                    },
                    searching: function() {
                        return "Đang tìm kiếm...";
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    return $('<span><i class="fas fa-layer-group me-2"></i>' + data.text + '</span>');
                },
                templateSelection: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    return $('<span><i class="fas fa-layer-group me-1"></i>' + data.text + '</span>');
                }
            }).on('change', function() {
                applyDepartmentModuleFilter();
            }).on('select2:open', function() {
                setTimeout(function() {
                    $('.select2-search__field').focus();
                }, 100);
            });

            // Xử lý chọn tất cả permissions
            $('#select_all_permissions_department').on('change', function() {
                const isChecked = $(this).prop('checked');
                const visibleCheckboxes = $('.department-permission-checkbox:visible');

                visibleCheckboxes.prop('checked', isChecked);
            });

            // Cập nhật trạng thái nút chọn tất cả khi các checkbox riêng lẻ thay đổi
            $(document).on('change', '.department-permission-checkbox:visible', function() {
                const visibleCheckboxes = $('.department-permission-checkbox:visible');
                const checkedCheckboxes = visibleCheckboxes.filter(':checked');

                $('#select_all_permissions_department').prop({
                    'checked': checkedCheckboxes.length === visibleCheckboxes.length,
                    'indeterminate': checkedCheckboxes.length > 0 && checkedCheckboxes.length <
                        visibleCheckboxes.length
                });
            });

            // Cập nhật trạng thái nút chọn tất cả khi filter thay đổi
            function updateSelectAllState() {
                const visibleCheckboxes = $('.department-permission-checkbox:visible');
                const checkedCheckboxes = visibleCheckboxes.filter(':checked');

                $('#select_all_permissions_department').prop({
                    'checked': checkedCheckboxes.length === visibleCheckboxes.length && visibleCheckboxes
                        .length > 0,
                    'indeterminate': checkedCheckboxes.length > 0 && checkedCheckboxes.length <
                        visibleCheckboxes.length
                });
            }

            // Function để apply department module filter
            function applyDepartmentModuleFilter() {
                const selectedModules = $('#department_module_filter').val() || [];
                let visibleModulesCount = 0;
                let visiblePermissionsCount = 0;

                // Nếu không có module nào được chọn, hiển thị tất cả
                if (selectedModules.length === 0) {
                    $('.department-permission-row').show();
                    $('.department-module-cell').show();

                    // Đếm tất cả các module và permissions
                    visibleModulesCount = $('.department-module-cell').length;
                    visiblePermissionsCount = $('.department-permission-row').length;
                } else {
                    // Ẩn tất cả rows trước
                    $('.department-permission-row').hide();
                    $('.department-module-cell').hide();

                    // Hiển thị các rows của modules được chọn
                    selectedModules.forEach(function(moduleName) {
                        const moduleRows = $(`.department-permission-row[data-module="${moduleName}"]`);
                        moduleRows.show();

                        if (moduleRows.length > 0) {
                            visibleModulesCount++;
                            visiblePermissionsCount += moduleRows.length;

                            // Hiển thị module cell cho module đầu tiên
                            const firstRow = moduleRows.first();
                            const moduleCell = firstRow.find(
                                `.department-module-cell[data-module="${moduleName}"]`);
                            moduleCell.attr('rowspan', moduleRows.length).show();
                        }
                    });
                }

                // Update counts
                $('#department_visible_modules_count').text(visibleModulesCount);
                $('#department_visible_permissions_count').text(visiblePermissionsCount);

                // Cập nhật trạng thái nút chọn tất cả
                updateSelectAllState();

                // Hiển thị thông báo nếu không có module hiển thị
                if (visibleModulesCount === 0) {
                    if ($('#department_no_modules_message').length === 0) {
                        $('#department_permissions_table_body').append(
                            '<tr id="department_no_modules_message"><td colspan="3" class="text-center text-muted py-4">' +
                            '<i class="fas fa-info-circle fa-2x mb-2"></i><br>' +
                            'Vui lòng chọn ít nhất một danh mục để hiển thị quyền' +
                            '</td></tr>'
                        );
                    }
                } else {
                    $('#department_no_modules_message').remove();
                }
            }

            // Nếu đã có department được chọn khi load trang, hiển thị bảng permissions
            if ($('#department_select').val()) {
                $('#department_permissions_table').removeClass('d-none');
            }

            // Mặc định hiển thị tất cả các module khi load trang
            applyDepartmentModuleFilter();
        });
    </script>
@endpush
