<?php

namespace Modules\Statistic\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\PermissionEnum;
use Modules\Statistic\Services\StatisticService;

class StatisticController extends Controller
{
    protected $statisticService;

    public function __construct(
        StatisticService $statisticService,
    ) {
        $this->statisticService = $statisticService;
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

    /**
     * Business Dashboard
     */
    // Controller
    public function dashboard_business()
    {
        can(PermissionEnum::DASHBOARD_VIEW);

        $filters = [
            'sales_person_id' => request('sales_person_id'),
            'person_incharge_id' => request('person_incharge_id'),
            // Thêm dòng này: Lấy date_from, mặc định là tháng-năm hiện tại
            'date_from' => request('date_from', date('Y-m')),
        ];

        $data = $this->statisticService->getBusinessDashboardStats(Auth::user(), $filters);

        return view('core::dashboard-business', $data);
    }
}
