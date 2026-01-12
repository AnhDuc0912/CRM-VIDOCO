<?php

namespace Modules\SellContract\Http\Controllers;

use Modules\Core\Enums\PermissionEnum;
use Modules\Core\Http\Controllers\Controller;
use Modules\Customer\Services\CustomerService;
use Modules\Employee\Services\EmployeeService;
use Modules\SellContract\Http\Requests\StoreSellContractRequest;
use Modules\Proposal\Services\ProposalService;
use Modules\SellContract\Services\SellContractService;

class SellContractController extends Controller
{
    protected $sellContractService;
    protected $customerService;
    protected $employeeService;
    protected $proposalService;

    public function __construct(SellContractService $sellContractService, CustomerService $customerService, EmployeeService $employeeService, ProposalService $proposalService)
    {
        $this->sellContractService = $sellContractService;
        $this->customerService = $customerService;
        $this->employeeService = $employeeService;
        $this->proposalService = $proposalService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sellContracts = $this->sellContractService->getSellContracts();
        return view('sellcontract::index', compact('sellContracts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return view
     */
    public function create()
    {
        can(PermissionEnum::SELL_CONTRACT_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Danh sách hợp đồng bán hàng',
                'url' => route('sell-contracts.index')
            ],
            [
                'title' => 'Thêm hợp đồng bán hàng',
                'url' => null
            ],
        ]);

        $customers = $this->customerService->getAllCustomers();
        $employees = $this->employeeService->getAllEmployees();
        $proposals = $this->proposalService->getProposals();
        $categories = $this->proposalService->getCategoriesForCreate();

        return view('sellcontract::create', compact('customers', 'employees', 'proposals', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSellContractRequest $request
     *
     * @return mixed
     */
    public function store(StoreSellContractRequest $request)
    {
        $data = $request->validated();
        $this->sellContractService->createSellContract($data);

        return redirect()->route('sell-contracts.index')->with('success', 'Hợp đồng bán hàng đã được tạo thành công');
    }

    public function edit($id)
    {
        can(PermissionEnum::SELL_CONTRACT_UPDATE);
        set_breadcrumbs([
            [
                'title' => 'Danh sách hợp đồng bán hàng',
                'url' => route('sell-contracts.index')
            ],
            [
                'title' => 'Chỉnh sửa hợp đồng bán hàng',
                'url' => null
            ],
        ]);
        $sellContract = $this->sellContractService->getSellContractById($id);
        if (!$sellContract) {
            return redirect()->route('sell-contracts.index')->with('error', 'Hợp đồng bán hàng không tồn tại');
        }
        
        $employees = $this->employeeService->getAllEmployees();
        $customers = $this->customerService->getAllCustomers();
        $proposals = $this->proposalService->getProposals();
        $categories = $this->proposalService->getCategoriesForCreate();

        return view('sellcontract::edit', compact('sellContract', 'customers', 'employees', 'proposals', 'categories'));
    }

    public function update(StoreSellContractRequest $request, $id)
    {
        $data = $request->validated();
        $this->sellContractService->updateSellContract($id, $data);

        return redirect()->route('sell-contracts.index')->with('success', 'Hợp đồng bán hàng đã được cập nhật thành công');
    }

    /**
     * Remove a file from a proposal.
     *
     * @param int $id
     * @param int $fileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFile($id, $fileId)
    {
        can(PermissionEnum::SELL_CONTRACT_UPDATE);
        $this->sellContractService->removeFile($id, $fileId);
        return redirect()->back()->with('success', 'File đã được xóa thành công');
    }

    /**
     * Download files of a sell contract.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function downloadFiles($id)
    {
        can(PermissionEnum::SELL_CONTRACT_DOWNLOAD_FILES);
        return $this->sellContractService->downloadFiles($id);
    }

    /**
     * Convert a sell contract to an order.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function convertToOrder($id)
    {
        can(PermissionEnum::SELL_CONTRACT_CONVERT_TO_ORDER);
        try {
            $this->sellContractService->convertToOrder($id);
            return redirect()->route('sell-contracts.index')->with('success', 'Hợp đồng bán hàng đã được chuyển thành đơn hàng');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return view
     */
    public function show($id)
    {
        set_breadcrumbs([
            [
                'title' => 'Danh sách hợp đồng bán hàng',
                'url' => route('sell-contracts.index')
            ],
            [
                'title' => 'Chi tiết hợp đồng bán hàng',
                'url' => null
            ],
        ]);
        can(PermissionEnum::SELL_CONTRACT_SHOW);
        $sellContract = $this->sellContractService->getSellContractById($id);
        if (!$sellContract) {
            return redirect()->route('sell-contracts.index')->with('error', 'Hợp đồng bán hàng không tồn tại');
        }
        $employees = $this->employeeService->getAllEmployees();
        $customers = $this->customerService->getAllCustomers();
        $proposals = $this->proposalService->getProposals();
        return view('sellcontract::show', compact('sellContract', 'employees', 'customers', 'proposals'));
    }
}
