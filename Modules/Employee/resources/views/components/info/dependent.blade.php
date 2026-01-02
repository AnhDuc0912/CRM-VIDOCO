@use('Modules\Core\Enums\RelationshipEnum')

<h5 class="mt-4 mb-3">Thông tin gia đình & người phụ thuộc</h5>

<div class="table-responsive">
    <table class="table mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Mối quan hệ</th>
                <th scope="col">Họ và tên</th>
                <th scope="col">Năm sinh</th>
                <th scope="col">Công việc</th>
                <th scope="col">Điện thoại liên lạc</th>
            </tr>
        </thead>
        <tbody>
            @if ($employee->dependents->count() > 0)
                @foreach ($employee->dependents as $key => $dependent)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ RelationshipEnum::getLabel((int) $dependent->relationship) ?? '' }}
                        </td>
                        <td>{{ $dependent->name ?? '' }}</td>
                        <td>{{ $dependent->birthday ? format_date($dependent->birthday) : '' }}
                        </td>
                        <td>{{ $dependent->job ?? '' }}</td>
                        <td>{{ $dependent->phone ?? '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
