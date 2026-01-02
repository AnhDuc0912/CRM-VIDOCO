<div class="row mb-4">
    <div class="col-12 col-lg-7">
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label">Bậc lương
                    hiện tại</label>
                <input type="text" readonly
                    value="{{ $employee->salary?->base_salary ?? '' }}"
                    class="form-control">
            </div>
            <div class="col-6">
                <label class="form-label">Lương cơ
                    bản</label>
                <input type="text" readonly
                    value="{{ format_money($employee->salary?->basic_salary ?? 0) }}"
                    class="form-control">
            </div>
            <div class="col-12">
                <h5 class="mt-4 mb-3">Các khoản phụ
                    cấp</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#
                            </th>
                            <th scope="col">Phụ
                                Cấp</th>
                            <th scope="col">Số
                                Tiền</th>
                            <th scope="col">Ghi
                                chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($employee->allowances) && $employee->allowances->count() > 0)
                            @foreach ($employee->allowances as $allowance)
                                <tr>
                                    <th scope="row">
                                        {{ $loop->iteration ?? '' }}
                                    </th>
                                    <td>{{ $allowance->name ?? '' }}
                                    </td>
                                    <td>{{ format_money($allowance->amount) }}
                                    </td>
                                    <td>{{ $allowance->note ?? '' }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">Không có
                                    dữ
                                    liệu</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 mt-4">
        <div class="row g-3">
            <div class="col-6">
                <label class="form-label">Lương
                    BHXH</label>
                <input type="text" readonly
                    value="{{ format_money($employee->salary?->insurance_salary ?? 0) }}"
                    class="form-control">
            </div>
        </div>
    </div>
</div>

<hr>

<div class="col-12">
    <h5 class="mt-4 mb-3">Chính sách phúc lợi khác
    </h5>
</div>
<div class="table-responsive">
    <table class="table mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Phúc lợi</th>
                <th scope="col">Giá Trị</th>
                <th scope="col">Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($employee->benefits) && $employee->benefits->count() > 0)
                @foreach ($employee->benefits as $benefit)
                    <tr>
                        <th scope="row">
                            {{ $loop->iteration ?? '' }}</th>
                        <td>{{ $benefit->name ?? '' }}</td>
                        <td>{{ format_money($benefit->amount) ?? '' }}
                        </td>
                        <td>{{ $benefit->note ?? '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">Không có
                        dữ liệu</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
