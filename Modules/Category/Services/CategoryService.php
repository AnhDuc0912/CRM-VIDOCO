<?php

namespace Modules\Category\Services;

use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Category\Enums\CategoryStatusEnum;
use Modules\Category\Repositories\Contracts\CategoryFileRepositoryInterface;
use Modules\Category\Repositories\Contracts\CategoryRepositoryInterface;
use Modules\Core\Enums\TemplateCodeEnum;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
        protected CategoryFileRepositoryInterface $categoryFileRepository,
    ) {}

    /**
     * Get all categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategories($params = [])
    {
        return $this->categoryRepository->getAllCategories($params);
    }

    /**
     * Get category by ID
     *
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getCategoryById($categoryId)
    {
        return $this->categoryRepository->find($categoryId);
    }

    /**
     * Create a new category
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createCategory(array $data)
    {
        $user = Auth::user();
        $data['code'] = generate_code(TemplateCodeEnum::CATEGORY, 'categories');
        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        if ($data['status'] == CategoryStatusEnum::ACTIVE) {
            $data['approved_by'] = $user->id;
            $data['approved_at'] = now();
        }
        try {
            DB::beginTransaction();
            $category = $this->categoryRepository->createCategory($data);

            if (!empty($data['files'])) {
                foreach ($data['files'] as $file) {
                    $path = 'categories/' . str_replace('/', '-', $category->code);
                    $file = FileHelper::uploadFile($file, $path);
                    $this->categoryFileRepository->storeCategoryFile([
                        'category_id' => $category->id,
                        'file_path' => $file['path'],
                        'extension' => $file['extension'],
                    ]);
                }
            }

            DB::commit();
            return $category;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Download category files as zip
     *
     * @param int $categoryId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function downloadFiles($categoryId)
    {
        try {
            $category = $this->categoryRepository->find($categoryId);

            if (!$category) {
                throw new \Exception('Không tìm thấy danh mục');
            }

            // Get all file paths for this category
            $filePaths = $category->files()->pluck('file_path')->toArray();

            if (empty($filePaths)) {
                throw new \Exception('Không có file nào để download');
            }

            // Create zip file (clean category code for filename)
            $cleanCode = str_replace(['/', '\\'], '-', $category->code);
            $zipName = 'category_' . $cleanCode . '_files';
            $result = FileHelper::createZipFromFiles($filePaths, $zipName, 'public');
            if (!$result['success']) {
                Log::error('Failed to create zip file for category: ' . $category->id, [
                    'error' => $result['error'],
                    'errors' => $result['errors'] ?? []
                ]);

                return redirect()->back()->with('error', 'Không thể tạo file zip: ' . $result['error']);
            }

            // Return download response
            $downloadResponse = FileHelper::downloadZip($result['file_path'], $result['file_name']);

            if (!$downloadResponse) {
                return redirect()->back()->with('error', 'Không thể tạo response download');
            }

            return $downloadResponse;
        } catch (\Exception $e) {
            Log::error('Error downloading category files', [
                'category_id' => $category ? $category->id : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi download files: ' . $e->getMessage());
        }
    }

    /**
     * Update category
     *
     * @param int $categoryId
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateCategory($categoryId, array $data)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $category = $this->categoryRepository->find($categoryId);
            if (!$category) {
                throw new \Exception('Không tìm thấy danh mục');
            }

            // Update basic info
            $updateData = [
                'name' => $data['name'],
                'status' => $data['status'],
                'updated_by' => $user->id,
            ];

            if ($data['status'] == CategoryStatusEnum::ACTIVE) {
                $updateData['approved_by'] = $user->id;
                $updateData['approved_at'] = now();
            }

            $category->update($updateData);

            if (!empty($data['files'])) {
                foreach ($data['files'] as $file) {
                    $path = 'categories/' . str_replace('/', '-', $category->code);
                    $file = FileHelper::uploadFile($file, $path);
                    $this->categoryFileRepository->storeCategoryFile([
                        'category_id' => $category->id,
                        'file_path' => $file['path'],
                        'extension' => $file['extension'],
                    ]);
                }
            }


            DB::commit();
            return $category;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Delete a file from a category.
     */
    public function deleteFile($fileId)
    {
        $file = $this->categoryFileRepository->findCategoryFile($fileId);
        $deleted = $this->categoryFileRepository->deleteFile($fileId);
        if ($deleted) {
            if (FileHelper::fileExists($file->file_path)) {
                FileHelper::deleteFile($file->file_path);
            }
        }
        return $deleted;
    }
}
