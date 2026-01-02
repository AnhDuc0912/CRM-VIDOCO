@extends('core::layouts.app')

@section('title', 'Danh Sách Báo Cáo Công Việc')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Công Việc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh Sách Báo Cáo</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('work.report.create', $work->id) }}" class="btn btn-secondary m-1">
                        <i class="bx bx-cloud-upload me-1"></i>Thêm Báo Cáo
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">

                @if ($reports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Ngày Báo Cáo</th>
                                    <th>Người Báo Cáo</th>
                                    <th>Trạng Thái</th>
                                    <th>Nội Dung</th>
                                    <th>File</th>
                                    <th class="text-center">Chức Năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $index => $report)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $report->report_date->format('d/m/Y H:i') }}</td>
                                        <td>{{ $report->user->full_name ?? 'Không rõ' }}</td>
                                        <td>{{ \Modules\Work\Models\WorkReport::STATUS_LABELS[$report->receiver_status] ?? 'Không xác định' }}
                                        </td>
                                        <td>{!! Str::limit(strip_tags($report->content), 60) !!}</td>
                                        <td>{{ $report->files ? $report->files->count() : 'Không có' }} file</td>
                                        <td class="text-center">
                                            <a href="{{ route('work.report.show', [$work->id, $report->id]) }}"
                                                class="btn btn-info m-1" title="Xem chi tiết">
                                                <i class="bx bx-info-square"></i>
                                            </a>

                                            <a href="{{ route('work.report.edit', [$work->id, $report->id]) }}"
                                                class="btn btn-secondary m-1" title="Chỉnh sửa">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Chưa có báo cáo nào cho công việc này.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
