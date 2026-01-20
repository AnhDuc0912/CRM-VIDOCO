<?php

namespace Modules\Statistic\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Customer\Services\CustomerService;
use Modules\Employee\Services\EmployeeService;
use Modules\Proposal\Models\Proposal;
use Carbon\Carbon;
use Modules\SellContract\Models\SellContract;
use Modules\SellOrder\Models\SellOrder;
use Modules\SellOrder\Models\SellOrderService as SellOrderServiceModel;
use Modules\SellOrder\Enums\SellOrderStatusEnum;
use Illuminate\Support\Facades\DB;

class StatisticService
{
    public function __construct(
        protected CustomerService $customerService,
        protected EmployeeService $employeeService,
    ) {}

    /**
     * Get business dashboard statistics
     *
     * @param Authenticatable $user
     * @param array $filters
     * @return array
     */
    public function getBusinessDashboardStats(Authenticatable $user, array $filters = []): array
    {
        // 1. Xử lý thời gian lọc (Mặc định là tháng hiện tại nếu không chọn)
        $dateFrom = $filters['date_from'] ?? date('Y-m');
        $parts = explode('-', $dateFrom);
        $year = $parts[0];
        $month = $parts[1];

        // 2. Tạo hàm Helper (Closure) để tái sử dụng bộ lọc cho các bảng khác nhau
        // Giúp code gọn hơn và đảm bảo tính nhất quán dữ liệu giữa các bảng
        $applyFilters = function ($query) use ($filters, $year, $month) {
            // Lọc theo tháng/năm
            $query->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);

            // Lọc theo Sales (nếu có)
            if (!empty($filters['sales_person_id'])) {
                $salesId = (int) $filters['sales_person_id'];
                // Lưu ý: Đảm bảo SellOrder và SellContract cũng có quan hệ 'customer'
                $query->whereHas('customer', function ($q) use ($salesId) {
                    $q->where('sales_person', $salesId);
                });
            }

            // Lọc theo Người phụ trách (nếu có)
            if (!empty($filters['person_incharge_id'])) {
                $inchargeId = (int) $filters['person_incharge_id'];
                $query->whereHas('customer', function ($q) use ($inchargeId) {
                    $q->where('person_incharge', $inchargeId);
                });
            }

            return $query;
        };

        // 3. Tính toán các chỉ số đếm (Có áp dụng bộ lọc)

        // Đếm Báo giá
        $proposalQuery = Proposal::query();
        $proposal_table_count = $applyFilters($proposalQuery)->count();

        // Đếm Hợp đồng
        $contractQuery = SellContract::query();
        $contract_table_count = $applyFilters($contractQuery)->count();

        // Đếm Đơn hàng
        $orderQuery = SellOrder::query();
        $order_table_count = $applyFilters($orderQuery)->count();

        // Đếm Báo giá thành công (Status 5 hoặc 7)
        $approvedProposalQuery = Proposal::whereIn('status', [5, 7]);
        $proposal_status_count = $applyFilters($approvedProposalQuery)->count();

        // 4. Tổng hợp dữ liệu vào mảng stats
        // Lấy dữ liệu cơ bản từ service cũ (nếu cần giữ logic cũ cho các phần khác)
        $stats = $this->customerService->getBusinessStats($user, $filters);

        // Ghi đè các chỉ số bằng số liệu đã lọc chuẩn xác ở trên
        $stats['proposal_count'] = $proposal_table_count;
        $stats['contract_count'] = $contract_table_count;
        $stats['order_count'] = $order_table_count;
        $stats['proposal_status_count'] = $proposal_status_count;

        // Tính tỷ lệ chuyển đổi
        $stats['proposal_status_ratio'] = $proposal_table_count > 0
            ? round(($proposal_status_count / $proposal_table_count) * 100, 1)
            : 0.0;

        // 5. Lấy danh sách nhân viên để hiển thị Dropdown
        $employees = $this->employeeService->getEmployeesByPosition("Kinh Doanh");

        // 6. Lấy danh sách báo giá chi tiết (Sử dụng lại bộ lọc đã tạo)
        $listProposalQuery = Proposal::with('customer')->orderBy('created_at', 'desc');
        $proposals = $applyFilters($listProposalQuery)->limit(50)->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'code' => $p->code ?? null,
                'amount' => $p->amount ?? 0,
                'customer' => $p->customer ? ($p->customer->company_name ?? trim(($p->customer->last_name ?? '') . ' ' . ($p->customer->first_name ?? ''))) : null,
                'created_at' => $p->created_at ? $p->created_at->toDateTimeString() : null,
            ];
        })->toArray();

        // Gán danh sách báo giá vào stats (nếu view cần biến này trong mảng stats, hoặc return riêng ở dưới)
        // $stats['proposals_list'] = $proposals; 

        // 7. Chuẩn hóa biểu đồ (Giữ nguyên logic cũ)
        $stats['daily_proposals'] = $this->normalizeDailySeries($stats['daily_proposals'] ?? [], 30);
        $stats['monthly_proposals'] = $this->normalizeMonthlySeries($stats['monthly_proposals'] ?? [], 12);
        $stats['yearly_proposals'] = $this->normalizeYearlySeries($stats['yearly_proposals'] ?? [], 5);

        // 8.1 Doanh thu theo Lĩnh vực (service field) cho các đơn hàng đã thanh toán (PAID)
        $revenueQuery = SellOrderServiceModel::query()
            ->selectRaw('service_fields.id as field_id, service_fields.name as field_name, SUM(sell_order_services.total) as revenue')
            ->join('sell_orders', 'sell_order_services.sell_order_id', '=', 'sell_orders.id')
            ->join('categories', 'sell_order_services.category_id', '=', 'categories.id')
            ->join('service_fields', 'categories.service_field_id', '=', 'service_fields.id')
            ->where('sell_orders.status', SellOrderStatusEnum::PAID)
            ->whereYear('sell_orders.created_at', $year)
            ->whereMonth('sell_orders.created_at', $month);

        // Áp dụng lọc theo Sales / Person in charge nếu có
        if (!empty($filters['sales_person_id']) || !empty($filters['person_incharge_id'])) {
            $revenueQuery->join('customers', 'sell_orders.customer_id', '=', 'customers.id');
            if (!empty($filters['sales_person_id'])) {
                $revenueQuery->where('customers.sales_person', (int) $filters['sales_person_id']);
            }
            if (!empty($filters['person_incharge_id'])) {
                $revenueQuery->where('customers.person_incharge', (int) $filters['person_incharge_id']);
            }
        }

        $revenue_by_service = $revenueQuery->groupBy('service_fields.id', 'service_fields.name')
            ->orderByDesc('revenue')
            ->get()
            ->map(function ($r) {
                return [
                    'field_id' => $r->field_id,
                    'field_name' => $r->field_name,
                    'revenue' => (float) $r->revenue,
                ];
            })->toArray();

        // 8. Tính toán so sánh tháng trước (Logic này cần đảm bảo hàm getPreviousMonthStats cũng nhận date_from để so sánh đúng tháng)
        $previousMonthStats = $this->getPreviousMonthStats($stats);

        // 9. Build Cards
        $cards = $this->buildCardsData($stats, $previousMonthStats);

        // Trả về dữ liệu, thêm biến $proposals nếu view cần danh sách riêng
        return compact('stats', 'employees', 'filters', 'cards', 'proposals', 'revenue_by_service');
    }

    /**
     * Build cards data with statistics and trends
     *
     * @param array $stats
     * @param array $previousMonthStats
     * @return array
     */
    private function buildCardsData(array $stats, array $previousMonthStats): array
    {
        return [
            [
                'label' => 'Người liên hệ',
                'value' => $stats['lead_count'] ?? 0,
                'value_short' => format_number_short($stats['lead_count'] ?? 0),
                'unit' => '',
                'color' => 'voilet',
                'percentage' => $this->calculatePercentageChange($stats['lead_count'] ?? 0, $previousMonthStats['lead_count'] ?? 0),
                'trend' => ($stats['lead_count'] ?? 0) >= ($previousMonthStats['lead_count'] ?? 0) ? 'up' : 'down'
            ],
            [
                'label' => 'Cơ hội kinh doanh',
                'value' => $stats['proposal_count'] ?? 0,
                'value_short' => format_number_short($stats['proposal_count'] ?? 0),
                'unit' => '',
                'color' => 'success',
                'percentage' => $this->calculatePercentageChange($stats['proposal_count'] ?? 0, $previousMonthStats['proposal_count'] ?? 0),
                'trend' => ($stats['proposal_count'] ?? 0) >= ($previousMonthStats['proposal_count'] ?? 0) ? 'up' : 'down'
            ],
            [
                'label' => 'Đơn hàng',
                'value' => $stats['order_count'] ?? 0,
                'value_short' => format_number_short($stats['order_count'] ?? 0),
                'unit' => '',
                'color' => 'info',
                'percentage' => $this->calculatePercentageChange($stats['order_count'] ?? 0, $previousMonthStats['order_count'] ?? 0),
                'trend' => ($stats['order_count'] ?? 0) >= ($previousMonthStats['order_count'] ?? 0) ? 'up' : 'down'
            ],
            [
                'label' => 'Khách hàng',
                'value' => $stats['customer_count'] ?? 0,
                'value_short' => format_number_short($stats['customer_count'] ?? 0),
                'unit' => '',
                'color' => 'primary-blue',
                'percentage' => $this->calculatePercentageChange($stats['customer_count'] ?? 0, $previousMonthStats['customer_count'] ?? 0),
                'trend' => ($stats['customer_count'] ?? 0) >= ($previousMonthStats['customer_count'] ?? 0) ? 'up' : 'down'
            ],
            [
                'label' => 'Dịch vụ',
                'value' => $stats['service_count'] ?? 0,
                'value_short' => format_number_short($stats['service_count'] ?? 0),
                'unit' => '',
                'color' => 'sunset',
                'percentage' => $this->calculatePercentageChange($stats['service_count'] ?? 0, $previousMonthStats['service_count'] ?? 0),
                'trend' => ($stats['service_count'] ?? 0) >= ($previousMonthStats['service_count'] ?? 0) ? 'up' : 'down'
            ],
            [
                'label' => 'Doanh thu',
                'value' => $stats['total_revenue'] ?? 0,
                'value_short' => format_number_short($stats['total_revenue'] ?? 0),
                'unit' => 'đ',
                'color' => 'danger',
                'percentage' => $this->calculatePercentageChange($stats['total_revenue'] ?? 0, $previousMonthStats['total_revenue'] ?? 0),
                'trend' => ($stats['total_revenue'] ?? 0) >= ($previousMonthStats['total_revenue'] ?? 0) ? 'up' : 'down'
            ],
        ];
    }

    /**
     * Get previous month statistics (simplified version)
     * Can be enhanced to fetch actual historical data from database
     *
     * @param array $currentStats
     * @return array
     */
    private function getPreviousMonthStats(array $currentStats): array
    {
        // Simplified: use current stats as base
        // In production, you might want to fetch from database with historical data
        return [
            'lead_count' => $currentStats['lead_count'] ?? 0,
            'proposal_count' => $currentStats['proposal_count'] ?? 0,
            'order_count' => $currentStats['order_count'] ?? 0,
            'customer_count' => $currentStats['customer_count'] ?? 0,
            'service_count' => $currentStats['service_count'] ?? 0,
            'total_revenue' => $currentStats['total_revenue'] ?? 0,
        ];
    }

    /**
     * Calculate percentage change between two values
     *
     * @param float|int $current
     * @param float|int $previous
     * @return string
     */
    private function calculatePercentageChange($current, $previous): string
    {
        if ($previous == 0) {
            return $current > 0 ? '+100%' : '0%';
        }
        $change = (($current - $previous) / $previous) * 100;
        return ($change >= 0 ? '+' : '') . number_format($change, 1) . '%';
    }

    /**
     * Normalize daily series to include last N days (keys: Y-m-d)
     *
     * @param array $series
     * @param int $days
     * @return array
     */
    private function normalizeDailySeries(array $series, int $days = 30): array
    {
        $result = [];
        $end = Carbon::now()->startOfDay();
        $start = (clone $end)->subDays($days - 1);

        for ($d = 0; $d < $days; $d++) {
            $date = $start->copy()->addDays($d)->format('Y-m-d');
            $result[$date] = isset($series[$date]) ? (int) $series[$date] : 0;
        }

        return $result;
    }

    /**
     * Normalize monthly series to include last N months (keys: Y-m)
     */
    private function normalizeMonthlySeries(array $series, int $months = 12): array
    {
        $result = [];
        $end = Carbon::now()->startOfMonth();
        $start = (clone $end)->subMonths($months - 1);

        for ($m = 0; $m < $months; $m++) {
            $monthKey = $start->copy()->addMonths($m)->format('Y-m');
            $result[$monthKey] = isset($series[$monthKey]) ? (int) $series[$monthKey] : 0;
        }

        return $result;
    }

    /**
     * Normalize yearly series to include last N years (keys: Y)
     */
    private function normalizeYearlySeries(array $series, int $years = 5): array
    {
        $result = [];
        $end = Carbon::now()->startOfYear();
        $start = (clone $end)->subYears($years - 1);

        for ($y = 0; $y < $years; $y++) {
            $yearKey = $start->copy()->addYears($y)->format('Y');
            $result[$yearKey] = isset($series[$yearKey]) ? (int) $series[$yearKey] : 0;
        }

        return $result;
    }
}
