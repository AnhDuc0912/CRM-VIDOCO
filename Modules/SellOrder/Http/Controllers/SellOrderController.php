<?php

namespace Modules\SellOrder\Http\Controllers;

use Modules\Core\Enums\PermissionEnum;
use Modules\Core\Http\Controllers\Controller;
use Modules\Customer\Services\CustomerService;
use Modules\Employee\Services\EmployeeService;
use Modules\Proposal\Services\ProposalService;
use Modules\SellOrder\Http\Requests\StoreSellOrderRequest;
use Modules\SellOrder\Services\SellOrderService;
use Modules\Category\Services\CategoryService;
use Modules\SellContract\Services\SellContractService;

class SellOrderController extends Controller
{
    protected $sellOrderService;
    protected $customerService;
    protected $employeeService;
    protected $proposalService;
    protected $categoryService;
    protected $contractService;

    public function __construct(SellOrderService $sellOrderService, CustomerService $customerService, EmployeeService $employeeService, ProposalService $proposalService, CategoryService $categoryService, SellContractService $contractService)
    {
        $this->sellOrderService = $sellOrderService;
        $this->customerService = $customerService;
        $this->employeeService = $employeeService;
        $this->proposalService = $proposalService;
        $this->categoryService = $categoryService;
        $this->contractService = $contractService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sellOrders = $this->sellOrderService->getSellOrders();
        return view('sellorder::index', compact('sellOrders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return view
     */
    public function create()
    {
        can(PermissionEnum::SELL_ORDER_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Danh sách đơn hàng',
                'url' => route('sell-orders.index')
            ],
            [
                'title' => 'Thêm đơn hàng',
                'url' => null
            ],
        ]);

        $customers = $this->customerService->getAllCustomers();
        $employees = $this->employeeService->getAllEmployees();
        $proposals = $this->proposalService->getProposals();
        $categories = $this->proposalService->getCategoriesForCreate();
        $proposalId = request()->get('proposal_id');

        return view('sellorder::create', compact('customers', 'employees', 'proposals', 'categories', 'proposalId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSellOrderRequest $request
     *
     * @return mixed
     */
    public function store(StoreSellOrderRequest $request)
    {
        $data = $request->validated();
        $this->sellOrderService->createSellOrder($data);
        // Cập nhật trạng thái proposal nếu có proposal_id
        if (!empty($data['proposal_id'])) {
            $this->proposalService->convertToOrder($data['proposal_id']);
        }

        return redirect()->route('sell-orders.index')->with('success', 'Đơn hàng đã được tạo thành công');
    }

    public function edit($id)
    {
        can(PermissionEnum::SELL_ORDER_UPDATE);
        set_breadcrumbs([
            [
                'title' => 'Danh sách đơn hàng',
                'url' => route('sell-orders.index')
            ],
            [
                'title' => 'Chỉnh sửa đơn hàng',
                'url' => null
            ],
        ]);
        $sellOrder = $this->sellOrderService->getSellOrderById($id);
            if (!empty($data['proposal_id'])) {
                $data['source_type'] = 'proposal';
                $data['source_id'] = $data['proposal_id'];
            }
        if (!$sellOrder) {
            return redirect()->route('sell-orders.index')->with('error', 'Đơn hàng không tồn tại');
        }
        $employees = $this->employeeService->getAllEmployees();
        $customers = $this->customerService->getAllCustomers();
        $proposals = $this->proposalService->getProposals();
        $categories = $this->proposalService->getCategoriesForCreate();

        return view('sellorder::edit', compact('sellOrder', 'customers', 'employees', 'proposals', 'categories'));
    }

    public function update(StoreSellOrderRequest $request, $id)
    {
        $data = $request->validated();
        $this->sellOrderService->updateSellOrder($id, $data);

        return redirect()->route('sell-orders.index')->with('success', 'Đơn hàng đã được cập nhật thành công');
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
        can(PermissionEnum::SELL_ORDER_UPDATE);
        $this->sellOrderService->removeFile($id, $fileId);
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
        can(PermissionEnum::SELL_ORDER_DOWNLOAD_FILES);
        return $this->sellOrderService->downloadFiles($id);
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
                'title' => 'Danh sách đơn hàng',
                'url' => route('sell-orders.index')
            ],
            [
                'title' => 'Chi tiết đơn hàng',
                'url' => null
            ],
        ]);
        can(PermissionEnum::SELL_ORDER_SHOW);
        $sellOrder = $this->sellOrderService->getSellOrderById($id);
        if (!$sellOrder) {
            return redirect()->route('sell-orders.index')->with('error', 'Đơn hàng không tồn tại');
        }
        $employees = $this->employeeService->getAllEmployees();
        $customers = $this->customerService->getAllCustomers();
        $proposals = $this->proposalService->getProposals();
        $categories = $this->proposalService->getCategoriesForCreate();

        return view('sellorder::show', compact('sellOrder', 'employees', 'customers', 'proposals', 'categories'));
    }
}
