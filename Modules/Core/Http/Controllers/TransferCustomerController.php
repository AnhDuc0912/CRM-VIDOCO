<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Enums\PermissionEnum;
use Modules\Core\Http\Controllers\Controller;
use Modules\Customer\Services\CustomerService;
use Modules\Employee\Services\EmployeeService;

class TransferCustomerController extends Controller
{
    public function __construct(
        private CustomerService $customerService,
        private EmployeeService $employeeService,
    ) {}

    /**
     * Show the transfer form
     */
    public function showForm()
    {
        can(PermissionEnum::CUSTOMER_UPDATE);
        set_breadcrumbs([
            ['title' => 'Cấu hình chung', 'url' => null],
            ['title' => 'Chuyển khách hàng', 'url' => null]
        ]);
        
        $salesPersons = $this->employeeService->getEmployeesByPosition('Kinh Doanh');

        return view('core::transfer-customer.form', compact('salesPersons'));
    }

    /**
     * Process transfer customers
     */
    public function process()
    {
        can(PermissionEnum::CUSTOMER_UPDATE);

        $validated = request()->validate([
            'from_employee_id' => 'required|integer|exists:employees,id',
            'to_employee_id' => 'required|integer|exists:employees,id|different:from_employee_id',
            'transfer_type' => 'required|in:sales_person,person_incharge',
        ], [
            'from_employee_id.required' => 'Vui lòng chọn nhân viên nguồn',
            'from_employee_id.exists' => 'Nhân viên nguồn không tồn tại',
            'to_employee_id.required' => 'Vui lòng chọn nhân viên đích',
            'to_employee_id.exists' => 'Nhân viên đích không tồn tại',
            'transfer_type.required' => 'Vui lòng chọn loại chuyển',
            'from_employee_id.different' => 'Nhân viên nguồn và đích phải khác nhau',
        ]);

        try {
            $count = $this->customerService->transferCustomersByEmployee(
                $validated['from_employee_id'],
                $validated['to_employee_id'],
                $validated['transfer_type']
            );

            return redirect()->route('transfer-customers.form')
                ->with('success', "Đã chuyển thành công {$count} khách hàng");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
