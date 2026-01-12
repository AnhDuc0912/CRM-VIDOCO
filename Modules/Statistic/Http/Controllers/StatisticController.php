<?php

namespace Modules\Statistic\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\PermissionEnum;
use Modules\Employee\Services\EmployeeService;

class StatisticController extends Controller
{
    protected $employeeService;
    public function __construct(
        EmployeeService $employeeService,
    ) {
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('statistic::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('statistic::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('statistic::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('statistic::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}


    /**
     * Dashboard
     */
    public function dashboard()
    {
        can(PermissionEnum::DASHBOARD_VIEW);
        return view('core::dashboard');
    }

    public function dashboard_business()
    {
        can(PermissionEnum::DASHBOARD_VIEW);
        $filters = [
            'sales_person_id' => request('sales_person_id'),
            'person_incharge_id' => request('person_incharge_id'),
        ];

        $stats = app(\Modules\Customer\Services\CustomerService::class)->getBusinessStats(Auth::user(), $filters);
        $employees = $this->employeeService->getEmployeesByPosition("Kinh Doanh");

        $cards = [
            ['label' => 'Người liên hệ', 'value' => $stats['lead_count'] ?? 0, 'value_short' => format_number_short($stats['lead_count'] ?? 0), 'unit' => '', 'color' => 'voilet', 'percentage' => '+23.4%', 'trend' => 'up'],
            ['label' => 'Cơ hội kinh doanh', 'value' => $stats['proposal_count'] ?? 0, 'value_short' => format_number_short($stats['proposal_count'] ?? 0), 'unit' => '', 'color' => 'success', 'percentage' => '-12.9%', 'trend' => 'down'],
            ['label' => 'Đơn hàng', 'value' => $stats['order_count'] ?? 0, 'value_short' => format_number_short($stats['order_count'] ?? 0), 'unit' => '', 'color' => 'info', 'percentage' => '+13.6%', 'trend' => 'up'],
            ['label' => 'Khách hàng', 'value' => $stats['customer_count'] ?? 0, 'value_short' => format_number_short($stats['customer_count'] ?? 0), 'unit' => '', 'color' => 'primary-blue', 'percentage' => '+14.7%', 'trend' => 'up'],
            ['label' => 'Dịch vụ', 'value' => $stats['service_count'] ?? 0, 'value_short' => format_number_short($stats['service_count'] ?? 0), 'unit' => '', 'color' => 'sunset', 'percentage' => '+13.6%', 'trend' => 'up'],
            ['label' => 'Doanh thu', 'value' => $stats['total_revenue'] ?? 0, 'value_short' => format_number_short($stats['total_revenue'] ?? 0), 'unit' => 'triệu', 'color' => 'danger', 'percentage' => '+15.2%', 'trend' => 'up'],
        ];

        return view('core::dashboard-business', compact('stats', 'employees', 'filters', 'cards'));
    }
}
