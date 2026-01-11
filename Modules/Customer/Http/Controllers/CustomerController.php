<?php

namespace Modules\Customer\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Enums\PermissionEnum;
use Modules\Core\Enums\RoleEnum;
use Modules\Customer\Http\Requests\UpdateOrCreateCustomerRequest;
use Modules\Customer\Models\CustomerNotificationHistories;
use Modules\Customer\Models\CustomerSource;
use Modules\Employee\Services\EmployeeService;
use Modules\Customer\Services\CustomerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{

    protected $customerService;
    protected $employeeService;

    public function __construct(CustomerService $customerService, EmployeeService  $employeeService)
    {
        $this->customerService = $customerService;
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Allow either general view or incharge-only access
        if (!Gate::allows(PermissionEnum::CUSTOMER_VIEW) && !Gate::allows(PermissionEnum::CUSTOMER_INCHARGE)) {
            abort(403);
        }
        set_breadcrumbs([
            [
                'title' => 'Khách hàng',
                'url' => null
            ],
        ]);

        $segment = request('segment');
        $customers = $this->customerService->getCustomersForUser(Auth::user(), $segment);
        return view('customer::index', compact('customers', 'segment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        can(PermissionEnum::CUSTOMER_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Khách hàng',
                'url' => route('customers.index')
            ],
            [
                'title' => 'Thêm mới khách hàng',
                'url' => null
            ],
        ]);

        $salesPersons = $this->employeeService->getEmployeesByPosition('Kinh Doanh');
        $customerSources = CustomerSource::where('is_active', 1)->get();

        return view('customer::create', compact('salesPersons', 'customerSources'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UpdateOrCreateCustomerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UpdateOrCreateCustomerRequest $request)
    {
        $request->validate([
            'personal.email'     => 'nullable|email|unique:customers,email',
            'personal.phone'     => 'nullable|unique:customers,phone',
            'personal.identity_card' => 'nullable|unique:customers,identity_card',
            'personal.invoice_tax_code'  => 'nullable|unique:customers,tax_code',
        ]);

        $request->validate([
            'company.email'     => 'nullable|email|unique:customers,email',
            'company.phone'     => 'nullable|unique:customers,phone',
            'company.identity_card' => 'nullable|unique:customers,identity_card',
            'company.invoice_tax_code'  => 'nullable|unique:customers,tax_code',
        ]);

        $data = $request->all();
        $this->customerService->createCustomer($data);
        return redirect()->route('customers.index')->with('success', 'Khách hàng đã được tạo thành công');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return view
     */
    public function edit($id)
    {
        can(PermissionEnum::CUSTOMER_UPDATE);
        set_breadcrumbs([
            [
                'title' => 'Khách hàng',
                'url' => route('customers.index')
            ],
            [
                'title' => 'Cập nhật khách hàng',
                'url' => null
            ],
        ]);

        $customer = $this->customerService->getCustomerById($id);
        $salesPersons = $this->employeeService->getEmployeesByPosition('Kinh Doanh');
        $customerSources = CustomerSource::where('is_active', 1)->get();

        return view('customer::edit', compact('customer', 'salesPersons', 'customerSources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrCreateCustomerRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrCreateCustomerRequest $request, $id)
    {
        $request->validate([
            'personal.email'     => 'nullable|email|unique:customers,email,' . $id,
            'personal.phone'     => 'nullable|unique:customers,phone,' . $id,
            'personal.identity_card' => 'nullable|unique:customers,identity_card,' . $id,
            'personal.invoice_tax_code'  => 'nullable|unique:customers,tax_code,' . $id,
        ]);

        $request->validate([
            'company.email'     => 'nullable|email|unique:customers,email,' . $id,
            'company.phone'     => 'nullable|unique:customers,phone,' . $id,
            'company.identity_card' => 'nullable|unique:customers,identity_card,' . $id,
            'company.invoice_tax_code'  => 'nullable|unique:customers,tax_code,' . $id,
        ]);

        $data = $request->all();
        $this->customerService->updateCustomer($id, $data);
        return redirect()->route('customers.index')->with('success', 'Khách hàng đã được cập nhật thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return view
     */
    public function show($id)
    {
        can(PermissionEnum::CUSTOMER_SHOW);
        
        $customer = $this->customerService->getCustomerById($id);
        $user = Auth::user();
        
        // Kiểm tra quyền xem chi tiết khách hàng
        $canView = false;
        
        // Có quyền xem tất cả khách hàng (CEO, Quản lý, Kế toán)
        if (Gate::allows(PermissionEnum::CUSTOMER_SHOW_ALL)) {
            $canView = true;
        }
        // Sale - chỉ xem khách hàng mà mình thêm (sales_person)
        else if ($user && $user->employee && $customer->sales_person === $user->employee->id) {
            $canView = true;
        }
        // CSKH - chỉ xem khách hàng được giao chăm sóc (person_incharge)
        else if ($user && $user->employee && $customer->person_incharge === $user->employee->id) {
            $canView = true;
        }
        
        // Nếu không có quyền, throw exception
        if (!$canView) {
            abort(403, 'Bạn không có quyền xem chi tiết khách hàng này');
        }
        
        set_breadcrumbs([
            [
                'title' => 'Khách hàng',
                'url' => route('customers.index')
            ],
            [
                'title' => 'Chi tiết khách hàng',
                'url' => null
            ],
        ]);

        $salesPersons = $this->employeeService->getEmployeesByPosition('Kinh Doanh');
        $customerSources = CustomerSource::where('is_active', 1)->get();

        return view('customer::show', compact('customer', 'salesPersons', 'customerSources'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        can(PermissionEnum::CUSTOMER_DELETE);
        $this->customerService->deleteCustomer($id);
        return redirect()->route('customers.index')->with('success', 'Khách hàng đã được xóa thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return view
     */
    public function showAjax($id)
    {
        $customer = $this->customerService->getCustomerById($id);

        return response()->json($customer);
    }

    /**
     * Remove the specified file from storage.
     *
     * @param int $id
     * @param int $fileId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFile($id, $fileId)
    {
        can(PermissionEnum::CUSTOMER_UPDATE);
        $this->customerService->removeFile($id, $fileId);
        return redirect()->back()->with('success', 'File đã được xóa thành công');
    }

    /**
     * Download files from customer
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadFiles($id)
    {
        return $this->customerService->downloadFiles($id);
    }

    public function notification()
    {
        $logs = CustomerNotificationHistories::latest()->paginate(20);
        return view('customer::notification', compact('logs'));
    }
}
