@use('Modules\Core\Enums\RelationshipEnum')

<h5 class="mt-4 mb-3">Thông tin gia đình & người phụ thuộc</h5>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div id="dependents-container">
            @if (!empty($employee->dependents) && $employee->dependents->count() > 0)
                @foreach ($employee->dependents as $key => $dependent)
                    <input type="hidden"
                        name="dependent[{{ $key }}][id]"
                        value="{{ $dependent->id }}">
                    <div class="dependent-row row g-3 mb-3"
                        data-index="{{ $key }}">
                        <div class="col-1">
                            <label for="validationServer03"
                                class="form-label">Quan hệ</label>
                            <select
                                name="dependent[{{ $key }}][relationship]"
                                class="form-select" required>
                                <option value="1"
                                    {{ $dependent->relationship == RelationshipEnum::FATHER ? 'selected' : '' }}>
                                    Bố</option>
                                <option value="2"
                                    {{ $dependent->relationship == RelationshipEnum::MOTHER ? 'selected' : '' }}>
                                    Mẹ</option>
                                <option value="3"
                                    {{ $dependent->relationship == RelationshipEnum::WIFE ? 'selected' : '' }}>
                                    Vợ</option>
                                <option value="4"
                                    {{ $dependent->relationship == RelationshipEnum::HUSBAND ? 'selected' : '' }}>
                                    Chồng
                                </option>
                                <option value="5"
                                    {{ $dependent->relationship == RelationshipEnum::CHILD ? 'selected' : '' }}>
                                    Con
                                </option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="validationServer02"
                                class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text"
                                name="dependent[{{ $key }}][name]"
                                value="{{ old('dependent.' . $key . '.name', $dependent->name) ?? '' }}"
                                class="form-control" required>
                        </div>
                        <div class="col-2">
                            <label for="validationServer02"
                                class="form-label">Năm sinh <span class="text-danger">*</span></label>
                            <input type="date"
                                name="dependent[{{ $key }}][birthday]"
                                value="{{ old('dependent.' . $key . '.birthday', $dependent->birthday ?? '') }}"
                                class="form-control" required>
                        </div>
                        <div class="col-3">
                            <label for="validationServer02"
                                class="form-label">Nghề nghiệp</label>
                            <input type="text"
                                name="dependent[{{ $key }}][job]"
                                value="{{ old('dependent.' . $key . '.job', $dependent->job) ?? '' }}"
                                class="form-control">
                        </div>
                        <div class="col-2">
                            <label for="validationServer02"
                                class="form-label">Điện thoại</label>
                            <input type="text"
                                name="dependent[{{ $key }}][phone]"
                                value="{{ old('dependent.' . $key . '.phone', $dependent->phone) ?? '' }}"
                                class="form-control">
                        </div>
                        <div class="col-1">
                            <button type="button"
                                class="btn btn-danger mt-4 remove-dependent">
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
        <button type="button" class="btn btn-info" id="add-dependent">Thêm Trường</button>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let dependentIndex =
                {{ !empty($employee->dependents) ? $employee->dependents->count() : 0 }};

            // Template cho row mới
            function getDependentRowTemplate(index) {
                return `
            <div class="dependent-row row g-3 mb-3" data-index="${index}">
                <div class="col-1">
                    <label for="validationServer03" class="form-label">Quan hệ</label>
                    <select name="dependent[${index}][relationship]" class="form-select" required>
                        <option value="1">Bố</option>
                        <option value="2">Mẹ</option>
                        <option value="3">Vợ</option>
                        <option value="4">Chồng</option>
                        <option value="5">Con</option>
                    </select>
                </div>
                <div class="col-3">
                    <label for="validationServer02" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="dependent[${index}][name]" class="form-control" required>
                </div>
                <div class="col-2">
                    <label for="validationServer02" class="form-label">Năm sinh <span class="text-danger">*</span></label>
                    <input type="date" name="dependent[${index}][birthday]" class="form-control" required>
                </div>
                <div class="col-3">
                    <label for="validationServer02" class="form-label">Nghề nghiệp</label>
                    <input type="text" name="dependent[${index}][job]" class="form-control">
                </div>
                <div class="col-2">
                    <label for="validationServer02" class="form-label">Điện thoại</label>
                    <input type="text" name="dependent[${index}][phone]" class="form-control">
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger mt-4 remove-dependent">
                        <i class="bx bx-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
            }

            // Thêm row mới
            $('#add-dependent').on('click', function() {
                const newRow = getDependentRowTemplate(
                    dependentIndex);
                $('#dependents-container').append(newRow);
                dependentIndex++;

                // Ẩn thông báo "Không có dữ liệu" nếu có
                $('#dependents-container .col-12 p.text-center')
                    .hide();
            });

            // Xóa row
            $(document).on('click', '.remove-dependent', function() {
                $(this).closest('.dependent-row').remove();

                // Kiểm tra nếu không còn row nào thì hiển thị thông báo
                if ($('.dependent-row').length === 0) {
                    $('#dependents-container').html(
                        '<div class="col-12"><p class="text-center">Không có dữ liệu</p></div>'
                    );
                }
            });
        });
    </script>
@endpush
