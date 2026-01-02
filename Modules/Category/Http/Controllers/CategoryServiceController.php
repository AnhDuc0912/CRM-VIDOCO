<?php

namespace Modules\Category\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Modules\Category\Services\CategoryServiceService;
use Modules\Category\Services\CategoryService;
use Modules\Category\Http\Requests\StoreCategoryServiceRequest;
use Modules\Core\Enums\PermissionEnum;
use Modules\Category\Http\Requests\CategoryServiceListRequest;
use Modules\Category\Http\Requests\UpdateCategoryServiceRequest;
use Modules\Category\Models\CategoryService as ModelsCategoryService;

class CategoryServiceController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected CategoryServiceService $categoryServiceService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(CategoryServiceListRequest $request)
    {
        can(PermissionEnum::SERVICE_VIEW);
        set_breadcrumbs([
            [
                'title' => 'Danh mục dịch vụ',
                'url' => null,
            ],
        ]);

        $services = $this->categoryServiceService->getServices($request->validated());

        return view('category::services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        can(PermissionEnum::SERVICE_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Thêm mới dịch vụ',
                'url' => null,
            ],
        ]);

        $categories = $this->categoryService->getCategories();

        return view('category::services.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryServiceRequest $request)
    {
        can(PermissionEnum::SERVICE_CREATE);
        try {
            $this->categoryServiceService->createService($request->validated());

            return redirect()
                ->route('services.index')
                ->with('success', 'Tạo dịch vụ thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Show the detail of a service.
     */
    public function show($id)
    {
        can(PermissionEnum::SERVICE_VIEW);
        set_breadcrumbs([
            [
                'title' => 'Chi tiết dịch vụ',
                'url' => null,
            ],
        ]);
        $service = $this->categoryServiceService->getServiceById($id);
        if (!$service) {
            return redirect()->route('services.index')->with('error', 'Không tìm thấy dịch vụ');
        }

        return view('category::services.show', compact('service'));
    }

    /**
     * Show the form for editing a service.
     */
    public function edit($id)
    {
        can(PermissionEnum::SERVICE_UPDATE);
        set_breadcrumbs([
            [
                'title' => 'Cập nhật dịch vụ',
                'url' => null,
            ],
        ]);

        $service = $this->categoryServiceService->getServiceById($id);
        if (!$service) {
            return redirect()->route('services.index')->with('error', 'Không tìm thấy dịch vụ');
        }

        $categories = $this->categoryService->getCategories();

        return view('category::services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(UpdateCategoryServiceRequest $request, $id)
    {
        can(PermissionEnum::SERVICE_UPDATE);

        try {
            $this->categoryServiceService->updateService($id, $request->validated());

            return redirect()->route('services.show', $id)->with('success', 'Cập nhật dịch vụ thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $service = ModelsCategoryService::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Xóa dịch vụ thành công!');
    }
}
