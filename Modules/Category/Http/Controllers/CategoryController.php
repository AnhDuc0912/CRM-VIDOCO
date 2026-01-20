<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Category\Services\CategoryService;
use Modules\Core\Enums\PermissionEnum;
use Modules\Category\Http\Requests\CategoryListRequest;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryServiceFieldRepository;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected CategoryServiceFieldRepository $serviceFieldRepository,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(CategoryListRequest $request)
    {
        can(PermissionEnum::CATEGORY_VIEW);
        set_breadcrumbs([
            [
                'title' => 'Danh mục dịch vụ',
                'url' => null,
            ],
        ]);

        $categories = $this->categoryService->getCategories($request->validated());

        return view('category::index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        can(PermissionEnum::CATEGORY_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Danh mục dịch vụ',
                'url' => route('categories.index'),
            ],
            [
                'title' => 'Thêm mới danh mục',
                'url' => null,
            ],
        ]);

        $serviceFields = $this->serviceFieldRepository->all();

        return view('category::create', compact('serviceFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        can(PermissionEnum::CATEGORY_CREATE);
        $data = $request->validated();

        try {
            $this->categoryService->createCategory($data);
            return redirect()->route('categories.index')->with('success', 'Danh mục đã được tạo thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Download files of a category.
     */
    public function downloadFiles($categoryId)
    {
        can(PermissionEnum::CATEGORY_DOWNLOAD_FILES);

        try {
            $category = $this->categoryService->getCategoryById($categoryId);

            if (!$category) {
                return redirect()->back()->with('error', 'Không tìm thấy danh mục');
            }

            $downloadResponse = $this->categoryService->downloadFiles($categoryId);

            if (!$downloadResponse) {
                return redirect()->back()->with('error', 'Không thể tạo file download');
            }

            return $downloadResponse;
        } catch (\Exception $e) {
            Log::error('Controller download error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Delete a file from a category.
     */
    public function deleteFile($fileId)
    {
        can(PermissionEnum::CATEGORY_DELETE_FILES);
        try {
            $this->categoryService->deleteFile($fileId);
            return response()->json(['success' => true, 'message' => 'File đã được xóa thành công']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($categoryId)
    {
        can(PermissionEnum::CATEGORY_UPDATE);
        $category = $this->categoryService->getCategoryById($categoryId)->load('files', 'serviceField');

        set_breadcrumbs([
            [
                'title' => 'Danh mục dịch vụ',
                'url' => route('categories.index'),
            ],
            [
                'title' => 'Cập nhật danh mục',
                'url' => null,
            ],
        ]);

        $serviceFields = $this->serviceFieldRepository->all();

        return view('category::edit', compact('category', 'serviceFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $categoryId)
    {
        can(PermissionEnum::CATEGORY_UPDATE);
        $data = $request->validated();

        try {
            $this->categoryService->updateCategory($categoryId, $data);
            return redirect()->route('categories.index')->with('success', 'Danh mục đã được cập nhật thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     *
     * @param int $categoryId
     * @return view
     */
    public function show($categoryId)
    {
        can(PermissionEnum::CATEGORY_SHOW);
        set_breadcrumbs([
            [
                'title' => 'Danh mục dịch vụ',
                'url' => route('categories.index'),
            ],
            [
                'title' => 'Chi tiết danh mục',
                'url' => null,
            ],
        ]);

        $category = $this->categoryService->getCategoryById($categoryId)->load('serviceField');
        return view('category::show', compact('category'));
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
