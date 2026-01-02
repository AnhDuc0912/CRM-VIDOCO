@use('Modules\Core\Enums\AccountStatusEnum')
@use('Modules\Employee\Enums\JobPositionEnum')
@use('App\Helpers\FileHelper')

<div class="row">
    <div class="col-12 col-lg-7 border-right">
        <div class="d-md-flex align-items-center">
            <div class="mb-md-0 mb-3">
                <img src="{{ $employee->avatar ? FileHelper::getFileUrl($employee->avatar) : asset('assets/images/avatars/avatar-1.png') }}"
                    class="rounded-circle shadow" width="130" height="130" alt="" />
            </div>
            <div class="ms-md-4 flex-grow-1">
                <div class="d-flex align-items-center mb-1">
                    <h4 class="mb-0">{{ $employee->full_name }}</h4>
                    <p class="mb-0 ms-auto"></p>
                </div>
                <p class="mb-0 text-muted">
                    {{ JobPositionEnum::getLabel($employee->current_position ?? '') }}</p>
                <p class="text-primary"><i class='bx bx-buildings'></i>
                    {{ $employee->department?->name ?? '' }}
                </p>
                <button type="button" class="btn btn-outline-secondary ml-2">Mã
                    NV:
                    {{ $employee->code ?? '' }}</button>
                <button type="button" class="btn btn-info">VP HCM</button>

            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <table class="table table-sm table-borderless mt-md-0 mt-3">
            <tbody>
                <tr>
                    <th>Trạng thái:</th>
                    <td>
                        @if ($employee->user?->status == AccountStatusEnum::ACTIVE)
                            <button type="button" class="btn btn-info">
                                Đang làm việc
                            </button>
                        @else
                            <button type="button" class="btn btn-danger">
                                Ngừng làm việc
                            </button>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Sinh nhật:</th>
                    <td>{{ $employee->birthday ? $employee->birthday->format('d/m/Y') : '' }}
                    </td>
                </tr>
                <tr>
                    <th>Địa chỉ:</th>
                    <td>{{ $employee->current_address ?? '' }}</td>
                </tr>
                <tr>
                    <th>Ngày vào làm:</th>
                    <td>{{ $employee->contracts?->last()?->start_date ? format_date($employee->contracts?->last()?->start_date, 'd/m/Y') : '' }}
                    </td>
                </tr>
                <tr>
                    <th>Hạn hợp đồng:</th>
                    <td>{{ $employee->contracts?->last()?->end_date ? format_date($employee->contracts?->last()?->end_date, 'd/m/Y') : '' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
