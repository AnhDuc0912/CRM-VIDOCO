<?php

namespace Modules\Proposal\Http\Controllers;

use Modules\Core\Enums\PermissionEnum;
use Modules\Core\Http\Controllers\Controller;
use Modules\Customer\Services\CustomerService;
use Modules\Employee\Services\EmployeeService;
use Modules\Proposal\Http\Requests\StoreProposalRequest;
use Modules\Proposal\Services\ProposalService;
use Modules\Category\Services\CategoryService;

class ProposalController extends Controller
{
    protected $proposalService;
    protected $customerService;
    protected $employeeService;
    protected $categoryService;

    public function __construct(
        ProposalService $proposalService,
        CustomerService $customerService,
        EmployeeService $employeeService,
        CategoryService $categoryService
    ) {
        $this->proposalService = $proposalService;
        $this->customerService = $customerService;
        $this->employeeService = $employeeService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function index()
    {
        can(PermissionEnum::PROPOSAL_VIEW);
        set_breadcrumbs([
            [
                'title' => 'Danh sách báo giá',
                'url' => null
            ],
        ]);
        $proposals = $this->proposalService->getProposals();

        return view('proposal::index', compact('proposals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return view
     */
    public function create()
    {
        can(PermissionEnum::PROPOSAL_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Danh sách báo giá',
                'url' => route('proposals.index')
            ],
            [
                'title' => 'Thêm báo giá',
                'url' => null
            ],
        ]);

        $employees = $this->employeeService->getAllEmployees();
        $customers = $this->customerService->getAllCustomers();
        $categories = $this->proposalService->getCategoriesForCreate();

        return view('proposal::create', compact('customers', 'employees', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProposalRequest $request
     *
     * @return mixed
     */
    public function store(StoreProposalRequest $request)
    {
        $data = $request->validated();
        $this->proposalService->createProposal($data);

        return redirect()->route('proposals.index')->with('success', 'Báo giá đã được tạo thành công');
    }

    /**
     * Download files of a proposal.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function downloadFiles($id)
    {
        can(PermissionEnum::PROPOSAL_DOWNLOAD_FILES);
        return $this->proposalService->downloadFiles($id);
    }

    /**
     * Convert a proposal to an order.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function convertToOrder($id)
    {
        can(PermissionEnum::PROPOSAL_CONVERT_TO_ORDER);
        try {
            $this->proposalService->convertToOrder($id);
            return redirect()->route('proposals.index')->with('success', 'Báo giá đã được chuyển thành đơn hàng');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return view
     */
    public function show($id)
    {
        can(PermissionEnum::PROPOSAL_SHOW);
        set_breadcrumbs([
            [
                'title' => 'Danh sách báo giá',
                'url' => route('proposals.index')
            ],
            [
                'title' => 'Chi tiết báo giá',
                'url' => null
            ],
        ]);

        $proposal = $this->proposalService->getProposalById($id);
        return view('proposal::show', compact('proposal'));
    }

    public function edit($id)
    {
        can(PermissionEnum::PROPOSAL_UPDATE);
        set_breadcrumbs([
            [
                'title' => 'Danh sách báo giá',
                'url' => route('proposals.index')
            ],
            [
                'title' => 'Sửa báo giá',
                'url' => null
            ],
        ]);

        $proposal = $this->proposalService->getProposalById($id);
        $employees = $this->employeeService->getAllEmployees();
        $customers = $this->customerService->getAllCustomers();
        $categories = $this->proposalService->getCategoriesForCreate();

        return view('proposal::edit', compact('proposal', 'customers', 'employees', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProposalRequest $request, $id)
    {
        can(PermissionEnum::PROPOSAL_UPDATE);
        $data = $request->validated();
        $this->proposalService->updateProposal($id, $data);

        return redirect()->route('proposals.index')->with('success', 'Báo giá đã được cập nhật thành công');
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
        can(PermissionEnum::PROPOSAL_UPDATE);
        $this->proposalService->removeFile($id, $fileId);
        return redirect()->back()->with('success', 'File đã được xóa thành công');
    }

    /**
     * Get a proposal by id.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxShow($id)
    {
        $proposal = $this->proposalService->getProposalById($id);
        return response()->json($proposal);
    }
}
