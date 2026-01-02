@extends('core::layouts.app')

@section('title', 'Theo Dõi Báo Cáo')

@section('content')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Công Việc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a></li>
                        <li class="breadcrumb-item active">Theo Dõi Báo Cáo</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#to-me" role="tab">Báo Cáo Gửi Tôi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#from-me" role="tab">Báo Cáo Tôi Gửi</a>
                    </li>
                </ul>

                <form method="GET" action="{{ route('work.report.follow') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Chờ đọc</option>
                                <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Đã đọc</option>
                                <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Khen ngợi</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Lọc</button>
                        </div>
                    </div>
                </form>

                <div class="tab-content">

                    {{-- TAG 1: Báo cáo gửi tôi --}}
                    <div class="tab-pane fade show active" id="to-me" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Công Việc</th>
                                        <th>Người Báo Cáo</th>
                                        <th>Ngày Báo Cáo</th>
                                        <th>Trạng Thái</th>
                                        <th>Nội Dung</th>
                                        <th>File</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reportsToMe as $report)
                                        <tr>
                                            <td>{{ $report->id }}</td>
                                            <td>{{ $report->work->work_name }}</td>
                                            <td>{{ $report->user->employee->full_name }}</td>
                                            <td>{{ $report->report_date->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $report->receiver_status == 1 ? 'secondary' : ($report->receiver_status == 2 ? 'info' : 'success') }}">
                                                    {{ \Modules\Work\Models\WorkReport::STATUS_LABELS[$report->receiver_status] ?? '' }}
                                                </span>
                                            </td>
                                            <td class="text-start" style="max-width:300px">
                                                {!! Str::limit($report->content, 120) !!}
                                            </td>
                                            <td>
                                                @if ($report->files->count())
                                                    @foreach ($report->files as $file)
                                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                                            target="_blank">
                                                            <i class="bx bx-file"></i>
                                                        </a>
                                                    @endforeach
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('work.report.show', [$report->work_id, $report->id]) }}"
                                                    class="btn btn-sm btn-info"><i class="bx bx-info-square"></i></a>
                                                <a href="{{ route('work.report.edit', [$report->work_id, $report->id]) }}"
                                                    class="btn btn-sm btn-secondary"><i class="bx bx-edit"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAG 2: Báo cáo tôi gửi --}}
                    <div class="tab-pane fade" id="from-me" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Công Việc</th>
                                        <th>Người Gửi</th>
                                        <th>Ngày Gửi</th>
                                        <th>Trạng Thái</th>
                                        <th>Nội Dung</th>
                                        <th>File</th>
                                        <th>Chức Năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reportsFromMe as $report)
                                        <tr>
                                            <td>{{ $report->id }}</td>
                                            <td>{{ $report->work->work_name }}</td>
                                            <td>
                                                {{ $report->user->full_name ?? '—' }}
                                            </td>
                                            <td>{{ $report->report_date->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $report->receiver_status == 1 ? 'secondary' : ($report->receiver_status == 2 ? 'info' : 'success') }}">
                                                    {{ \Modules\Work\Models\WorkReport::STATUS_LABELS[$report->receiver_status] ?? '' }}
                                                </span>
                                            </td>
                                            <td class="text-start" style="max-width:300px">
                                                {!! Str::limit($report->content, 120) !!}
                                            </td>
                                            <td>
                                                @if ($report->files->count())
                                                    @foreach ($report->files as $file)
                                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                                            target="_blank">
                                                            <i class="bx bx-file"></i>
                                                        </a>
                                                    @endforeach
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('work.report.show', [$report->work_id, $report->id]) }}"
                                                    class="btn btn-sm btn-info"><i class="bx bx-info-square"></i></a>
                                                <a href="{{ route('work.report.edit', [$report->work_id, $report->id]) }}"
                                                    class="btn btn-sm btn-secondary"><i class="bx bx-edit"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
