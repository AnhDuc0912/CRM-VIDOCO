@use('Modules\Core\Enums\RelationshipEnum')

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="row">
            <div class="col-3">
                <label class="form-label">Bậc lương hiện tại <span class="text-danger">*</span></label>
                <input type="text" name="salary[base_salary]"
                    value="{{ old('salary.base_salary', $employee->salary?->base_salary ?? '') }}" class="form-control">
            </div>
            <div class="col-3">
                <label class="form-label">Lương cơ bản <span class="text-danger">*</span></label>
                <input type="text" name="salary[basic_salary]"
                    value="{{ old('salary.basic_salary', format_money((int) $employee->salary?->basic_salary ?? 0)) }}"
                    class="form-control">
            </div>
        </div>
        <div class="col-12">
            <h5 class="mt-4 mb-3">Các khoản phụ cấp
            </h5>
        </div>
        <div id="allowances-container">
            @if (!empty($employee->allowances) && $employee->allowances->count() > 0)
                @foreach ($employee->allowances as $key => $allowance)
                    <input type="hidden" name="allowance[{{ $key }}][id]" value="{{ $allowance->id }}">
                    <div class="allowance-row row g-3 mb-3" data-index="{{ $key }}">
                        <div class="col-3">
                            <label for="validationServer02" class="form-label required">Phụ cấp</label>
                            <input type="text" name="allowance[{{ $key }}][name]"
                                value="{{ old('allowance.' . $key . '.name', $allowance->name) ?? '' }}"
                                class="form-control" required>
                        </div>
                        <div class="col-2">
                            <label for="validationServer02" class="form-label required">Số tiền</label>
                            <input type="text" name="allowance[{{ $key }}][amount]"
                                value="{{ old('allowance.' . $key . '.amount', $allowance->amount) ?? '' }}"
                                class="form-control" required>
                        </div>
                        <div class="col-3">
                            <label for="validationServer02" class="form-label">Ghi chú</label>
                            <input type="text" name="allowance[{{ $key }}][note]"
                                value="{{ old('allowance.' . $key . '.note', $allowance->note) ?? '' }}"
                                class="form-control">
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-danger mt-4 remove-allowance">
                                <i class="bx bx-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-center">Không có dữ liệu</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12">
        <button type="button" class="btn btn-info" id="add-allowance">Thêm
            Phụ cấp</button>
    </div>

    <div class="col-12 col-lg-6">
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label">Lương BHXH</label>
                <input type="text" name="salary[insurance_salary]"
                    value="{{ old('salary.insurance_salary', format_money((int) $employee->salary?->insurance_salary ?? 0)) }}"
                    class="form-control">
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let allowanceIndex =
                {{ !empty($employee->allowances) ? $employee->allowances->count() : 0 }};

            // Template cho row mới
            function getAllowanceRowTemplate(index) {
                return `
            <div class="allowance-row row g-3 mb-3" data-index="${index}">
                <div class="col-3">
                    <label for="validationServer02" class="form-label">Phụ cấp <span class="text-danger">*</span></label>
                    <input type="text" name="allowance[${index}][name]" class="form-control" required>
                </div>
                <div class="col-2">
                    <label for="validationServer02" class="form-label">Số tiền <span class="text-danger">*</span></label>
                    <input type="text" name="allowance[${index}][amount]" class="form-control" required>
                </div>
                <div class="col-3">
                    <label for="validationServer02" class="form-label">Ghi chú</label>
                    <input type="text" name="allowance[${index}][note]" class="form-control">
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger mt-4 remove-allowance">
                        <i class="bx bx-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
            }

            // Thêm row mới
            $('#add-allowance').on('click', function() {
                const newRow = getAllowanceRowTemplate(
                    allowanceIndex);
                $('#allowances-container').append(newRow);
                allowanceIndex++;

                $('#allowances-container .col-12 p.text-center')
                    .hide();
            });

            // Xóa row
            $(document).on('click', '.remove-allowance', function() {
                $(this).closest('.allowance-row').remove();

                // Kiểm tra nếu không còn row nào thì hiển thị thông báo
                if ($('.allowance-row').length === 0) {
                    $('#allowances-container').html(
                        '<div class="col-12"><p class="text-center">Không có dữ liệu</p></div>'
                    );
                }
            });
        });
    </script>
@endpush
