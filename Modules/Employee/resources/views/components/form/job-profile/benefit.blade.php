@include('employee::components.form.job-profile.allowance')
<hr>

<div class="col-12">
    <h5 class="mt-4 mb-3">Chính sách phúc lợi khác
    </h5>
</div>
<div id="benefits-container">
    @if (!empty($employee->benefits) && $employee->benefits->count() > 0)
        @foreach ($employee->benefits as $key => $benefit)
            <input type="hidden" name="benefit[{{ $key }}][id]"
                value="{{ $benefit->id }}">
            <div class="benefit-row row g-3 mb-3"
                data-index="{{ $key }}">
                <div class="col-3">
                    <label for="validationServer02" class="form-label">Phụ cấp <span class="text-danger">*</span></label>
                    <input type="text"
                        name="benefit[{{ $key }}][name]"
                        value="{{ old('benefit.' . $key . '.name', $benefit->name) ?? '' }}"
                        class="form-control" required>
                </div>
                <div class="col-2">
                    <label for="validationServer02" class="form-label">Số tiền <span class="text-danger">*</span></label>
                    <input type="text"
                        name="benefit[{{ $key }}][amount]"
                        value="{{ old('benefit.' . $key . '.amount', $benefit->amount) ?? '' }}"
                        class="form-control" required>
                </div>
                <div class="col-3">
                    <label for="validationServer02" class="form-label">Ghi
                        chú</label>
                    <input type="text"
                        name="benefit[{{ $key }}][note]"
                        value="{{ old('benefit.' . $key . '.note', $benefit->note) ?? '' }}"
                        class="form-control">
                </div>
                <div class="col-1">
                    <button type="button"
                        class="btn btn-danger mt-4 remove-benefit">
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

<div class="col-12">
    <button type="button" class="btn btn-info" id="add-benefit">Thêm
        Phúc lợi</button>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let benefitIndex =
                {{ !empty($employee->benefits) ? $employee->benefits->count() : 0 }};

            // Template cho row mới
            function getBenefitRowTemplate(index) {
                return `
            <div class="benefit-row row g-3 mb-3" data-index="${index}">
                <div class="col-3">
                    <label for="validationServer02" class="form-label">Phụ cấp <span class="text-danger">*</span></label>
                    <input type="text" name="benefit[${index}][name]" class="form-control" required>
                </div>
                <div class="col-2">
                    <label for="validationServer02" class="form-label">Số tiền <span class="text-danger">*</span></label>
                    <input type="text" name="benefit[${index}][amount]" class="form-control" required>
                </div>
                <div class="col-3">
                    <label for="validationServer02" class="form-label">Ghi chú</label>
                    <input type="text" name="benefit[${index}][note]" class="form-control">
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger mt-4 remove-benefit">
                        <i class="bx bx-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
            }

            // Thêm row mới
            $('#add-benefit').on('click', function() {
                const newRow = getBenefitRowTemplate(
                    benefitIndex);
                $('#benefits-container').append(newRow);
                benefitIndex++;

                $('#benefits-container .col-12 p.text-center')
                    .hide();
            });

            // Xóa row
            $(document).on('click', '.remove-benefit', function() {
                $(this).closest('.benefit-row').remove();

                // Kiểm tra nếu không còn row nào thì hiển thị thông báo
                if ($('.benefit-row').length === 0) {
                    $('#benefits-container').html(
                        '<div class="col-12"><p class="text-center">Không có dữ liệu</p></div>'
                    );
                }
            });
        });
    </script>
@endpush
