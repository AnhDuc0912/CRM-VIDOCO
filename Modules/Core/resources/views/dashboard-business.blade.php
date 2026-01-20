@extends('core::layouts.app')
@use('Modules\Core\Enums\AccountStatusEnum')

@section('title', 'Dashboard')
@push('styles')
    <style>
        .font-12 {
            font-size: 12px !important;
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
                    <a href="{{ route('dashboard.business') }}" class="btn btn-light">Xóa lọc</a>
                </div>
            </form>
        </div>
    </div>
    {{-- Card thống kê --}}

    <div class="row g-3 mb-3">
        @foreach ($cards as $card)
            <div class="col-12 col-md-6 col-lg-4 col-xl">
                <div class="card radius-15 bg-{{ $card['color'] }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="mb-0 text-white">
                                    {{ $card['value_short'] }}
                                </h2>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <div>
                                <p class="mb-0 font-12 text-white">{{ $card['label'] }}</p>
                            </div>
                            <i
                                class="bx bxs-{{ $card['trend'] === 'up' ? 'up' : 'down' }}-arrow-alt font-14 text-white"></i>
                            <div class="ms-auto  font-12 text-white">{{ $card['percentage'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Thống kê báo giá, đơn hàng, hợp đồng --}}
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card radius-15">
                <div class="card-body" style="position: relative;">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0">Doanh thu nhóm dịch vụ</h5>
                        </div>
                    </div>

                    {{-- Vùng hiển thị biểu đồ --}}
                    <div id="chart2" style="min-height:300px; max-height:300px; position:relative;"></div>

                    @php
                        // 1. Chuẩn bị dữ liệu
                        $data = $revenue_by_service ?? [];
                        $labels = array_column($data, 'field_name');
                        $series = array_map('floatval', array_column($data, 'revenue'));
                        $totalRevenue = array_sum($series);

                        // 2. Định nghĩa bảng màu cố định (để đồng bộ giữa Chart và HTML Legend)
                        $palette = [
                            '#0d6efd',
                            '#6610f2',
                            '#6f42c1',
                            '#d63384',
                            '#dc3545',
                            '#fd7e14',
                            '#ffc107',
                            '#198754',
                            '#20c997',
                            '#0dcaf0',
                        ];

                        // Cắt bảng màu cho khớp số lượng data (nếu cần xử lý trong JS)
                        // Ở đây ta cứ truyền cả bảng màu vào chart, chart sẽ tự loop

                    @endphp

                    {{-- Phần Chú thích (Custom Legend) --}}
                    <div class="legends mt-3">
                        <div class="row">
                            @php $globalIndex = 0; @endphp
                            @foreach (array_chunk($data, 3) as $chunk)
                                <div class="col-12 col-lg-4">
                                    @foreach ($chunk as $row)
                                        @php
                                            $pct =
                                                $totalRevenue > 0
                                                    ? round(($row['revenue'] / $totalRevenue) * 100, 1)
                                                    : 0;
                                            // Lấy màu tương ứng theo index
                                            $color = $palette[$globalIndex % count($palette)];
                                            $globalIndex++;
                                        @endphp
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="text-secondary">
                                                {{-- Áp dụng màu vào icon --}}
                                                <i class="bx bxs-circle font-13 me-2"
                                                    style="color: {{ $color }}"></i>
                                                {{ $row['field_name'] }}
                                            </div>
                                            <div>{{ number_format($row['revenue'], 0) }}</div>
                                            <div class="text-secondary small">{{ $pct }}%</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card radius-15">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0">Tỷ Lệ Chuyển Đổi KH Tiềm Năng</h5>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end">
                        <form method="GET" action="{{ route('dashboard.business') }}" class="d-flex align-items-center">
                            <input type="month" name="date_from" class="form-control form-control-sm me-2"
                                {{-- Lưu ý: value phải có định dạng YYYY-MM (Ví dụ: 2023-10) --}} value="{{ $filters['date_from'] ?? date('Y-m') }}" />
                            <button type="submit" class="btn btn-sm btn-primary ms-2 w-100">Áp dụng</button>
                        </form>
                    </div>

                    <div class="row mt-3 g-2">
                        <div class="col-12 col-lg-6">
                            <div class="card radius-15 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Tổng báo giá</p>
                                        </div>
                                    </div>
                                    <h4 class="mb-0">{{ format_number_short($stats['proposal_count'] ?? 0) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card radius-15 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Tổng đơn hàng</p>
                                        </div>
                                    </div>
                                    <h4 class="mb-0">{{ format_number_short($stats['order_count'] ?? 0) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card radius-15 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Tổng hợp đồng</p>
                                        </div>
                                    </div>
                                    <h4 class="mb-0">{{ format_number_short($stats['contract_count'] ?? 0) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card radius-15 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Báo giá được duyệt</p>
                                        </div>
                                    </div>
                                    <h4 class="mb-0">{{ format_number_short($stats['proposal_status_count'] ?? 0) }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12">
                            <div class="card radius-15 border shadow-none">
                                <div class="card-body">
                                    <h4 class="mb-0">TỶ LỆ CHUYỂN ĐỔI: {{ $stats['proposal_status_ratio'] ?? 0 }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy dữ liệu từ PHP
            var labels = @json($labels);
            var series = @json($series);
            var palette = @json($palette); // Lấy bảng màu từ PHP xuống

            var chartEl = document.querySelector('#chart2');

            if (chartEl) {
                // Kiểm tra nếu có dữ liệu thì mới render
                if (Array.isArray(series) && series.length > 0 && series.some(val => val > 0)) {

                    var options = {
                        series: series,
                        labels: labels,
                        colors: palette, // QUAN TRỌNG: Gán màu để khớp với HTML Legend
                        chart: {
                            type: 'donut',
                            height: 300,
                            fontFamily: 'inherit' // Dùng font của web
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '70%', // Độ dày của vòng donut (càng lớn càng mỏng)
                                    labels: {
                                        show: true,
                                        name: {
                                            show: true,
                                            fontSize: '14px'
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '16px',
                                            formatter: function(val) {
                                                return new Intl.NumberFormat('vi-VN').format(val);
                                            }
                                        },
                                        total: {
                                            show: true,
                                            label: 'Tổng',
                                            formatter: function(w) {
                                                // Tính tổng tự động hiển thị ở giữa
                                                const total = w.globals.seriesTotals.reduce((a, b) => a + b,
                                                    0);
                                                return new Intl.NumberFormat('vi-VN', {
                                                    notation: "compact",
                                                    compactDisplay: "short"
                                                }).format(total);
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: false // Tắt số trên biểu đồ cho đỡ rối (vì đã có legend bên dưới)
                        },
                        legend: {
                            show: false // Tắt legend mặc định của ApexCharts
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent'] // Tạo khe hở giữa các lát cắt
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return new Intl.NumberFormat('vi-VN').format(val) + ' VNĐ';
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(chartEl, options);
                    chart.render();

                    // Lưu vào window để update sau này nếu cần
                    window.revenueChart = chart;

                } else {
                    // Hiển thị thông báo khi không có dữ liệu
                    chartEl.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        <i class="bx bx-info-circle me-2"></i> Chưa có dữ liệu doanh thu
                    </div>`;
                    chartEl.style.height = '300px'; // Giữ chiều cao để không bị giật layout
                }
            }
        });
    </script>
@endpush
