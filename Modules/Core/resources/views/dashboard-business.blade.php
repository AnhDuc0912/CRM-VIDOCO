@extends('core::layouts.app')
@use('Modules\Core\Enums\AccountStatusEnum')

@section('title', 'Dashboard')
@push('styles')
<style>
    .card {
        margin-bottom: 0;
    }
</style>
@endpush
@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form class="row gy-2 gx-3 align-items-end" method="GET" action="{{ route('dashboard.business') }}">
            <div class="col-md-4">
                <label class="form-label">Lọc theo Nhân viên Sale</label>
                <select name="sales_person_id" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}"
                            {{ (string) $filters['sales_person_id'] === (string) $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name ?? 'NV #' . $employee->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Lọc theo Nhân viên CSKH</label>
                <select name="person_incharge_id" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}"
                            {{ (string) $filters['person_incharge_id'] === (string) $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name ?? 'NV #' . $employee->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Lọc</button>
                <a href="{{ route('dashboard') }}" class="btn btn-light">Xóa lọc</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-lg-3 col-xl-3">
        <div class="card bg-primary radius-15">
            <div class="card-body">
                <p class="card-text text-white mb-1">Tổng khách hàng</p>
                <h3 class="mb-0 text-white">{{ $stats['total_customers'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3 col-xl-3">
        <div class="card bg-success radius-15">
            <div class="card-body">
                <p class="card-text text-white mb-1">Khách cá nhân</p>
                <h3 class="mb-0 text-white">{{ $stats['personal_count'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3 col-xl-3">
        <div class="card bg-info radius-15">
            <div class="card-body">
                <p class="card-text text-white mb-1">Khách công ty</p>
                <h3 class="mb-0 text-white">{{ $stats['company_count'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3 col-xl-3">
        <div class="card bg-warning radius-15">
            <div class="card-body">
                <p class="card-text text-white mb-1">Tổng doanh số</p>
                <h3 class="mb-0 text-white">{{ number_format($stats['total_revenue'] ?? 0) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-lg-4 col-xl-4">
        <div class="card bg-voilet radius-15">
            <div class="card-body">
                <p class="card-text text-white mb-1">Đang sử dụng</p>
                <h4 class="mb-0 text-white">{{ $stats['using_count'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 col-xl-4">
        <div class="card bg-secondary radius-15">
            <div class="card-body">
                <p class="card-text text-white mb-1">Chưa sử dụng</p>
                <h4 class="mb-0 text-white">{{ $stats['lead_count'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 col-xl-4">
        <div class="card bg-danger radius-15">
            <div class="card-body">
                <p class="card-text text-white mb-1">Rời bỏ</p>
                <h4 class="mb-0 text-white">{{ $stats['stopped_count'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card radius-12 border-0 shadow-sm mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Top 10 khách hàng doanh số cao nhất</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Khách hàng</th>
                        <th>Doanh số</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (($stats['top_customers'] ?? []) as $index => $customer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $customer['name'] ?? 'KH #' . ($customer['id'] ?? '') }}</td>
                            <td>{{ number_format($customer['revenue'] ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card radius-12 border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Khách hàng thêm mới</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <h6 class="text-secondary">Theo tháng</h6>
                <ul class="list-group list-group-flush">
                    @foreach ($stats['monthly_new'] ?? [] as $label => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $label }}</span>
                            <span class="fw-semibold">{{ $count }}</span>
                        </li>
                    @endforeach
                    @if (empty($stats['monthly_new']))
                        <li class="list-group-item text-muted">Không có dữ liệu</li>
                    @endif
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-secondary">Theo quý</h6>
                <ul class="list-group list-group-flush">
                    @foreach ($stats['quarterly_new'] ?? [] as $label => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $label }}</span>
                            <span class="fw-semibold">{{ $count }}</span>
                        </li>
                    @endforeach
                    @if (empty($stats['quarterly_new']))
                        <li class="list-group-item text-muted">Không có dữ liệu</li>
                    @endif
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-secondary">Theo năm</h6>
                <ul class="list-group list-group-flush">
                    @foreach ($stats['yearly_new'] ?? [] as $label => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $label }}</span>
                            <span class="fw-semibold">{{ $count }}</span>
                        </li>
                    @endforeach
                    @if (empty($stats['yearly_new']))
                        <li class="list-group-item text-muted">Không có dữ liệu</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Vector map JavaScript -->
<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-in-mill.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-us-aea-en.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-uk-mill-en.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-au-mill.js"></script>
<script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
<script src="assets/js/index2.js"></script>
@endpush
