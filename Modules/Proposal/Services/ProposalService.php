<?php

namespace Modules\Proposal\Services;

use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Order\Repositories\Contracts\CategoryRepositoryInterface;
use Modules\Proposal\Enums\ProposalStatusEnum;
use Modules\Proposal\Repositories\Contracts\ProposalRepositoryInterface;
use Modules\SellContract\Enums\SellContractStatusEnum;
use Modules\SellContract\Services\SellContractService;
use Modules\SellOrder\Services\SellOrderService;

class ProposalService
{
    protected $proposalRepository;
    protected $categoryRepository;
    protected $sellOrderService;
    protected $sellContractService;

    public function __construct(
        ProposalRepositoryInterface $proposalRepository, 
        CategoryRepositoryInterface $categoryRepository, 
        SellOrderService $sellOrderService,
        SellContractService $sellContractService
    )
    {
        $this->proposalRepository = $proposalRepository;
        $this->categoryRepository = $categoryRepository;
        $this->sellOrderService = $sellOrderService;
        $this->sellContractService = $sellContractService;
    }

    /**
     * Get all proposals
     *
     * @return Collection
     */
    public function getProposals()
    {
        return $this->proposalRepository->getProposals();
    }

    /**
     * Create a new proposal
     *
     * @param array $data
     *
     * @return mixed
     */
    public function createProposal(array $data)
    {
        $data['code'] = generate_code(TemplateCodeEnum::PROPOSAL, 'proposals');
        $data['created_by'] = Auth::user()->id;
        DB::beginTransaction();
        try {
            $data['amount'] = $this->calculateAmount($data['services'] ?? []);
            $proposal = $this->proposalRepository->create($data);
            if (isset($data['files'])) {
                $this->updateFiles($proposal, $data['files']);
            }

            if (isset($data['services'])) {
                foreach ($data['services'] as $service) {
                    $proposal->services()->create([
                        'category_id' => $service['category_id'] ?? null,
                        'service_id' => $service['service_id'] ?? null,
                        'product_id' => $service['product_id'] ?? null,
                        'price' => $service['price'],
                        'quantity' => $service['quantity'],
                        'total' => $service['total'] ?? ($service['price'] * $service['quantity']),
                        'proposal_id' => $proposal->id,
                    ]);
                }
            }

            DB::commit();
            return $proposal;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Calculate amount of proposal services
     *
     * @param array $proposalServices
     * @return float
     */
    public function calculateAmount($proposalServices)
    {
        $total = 0;
        foreach ($proposalServices as $service) {
            if (isset($service['total'])) {
                // Nếu có total, dùng total (đã format)
                $cleanTotal = is_string($service['total']) ?
                    (int)str_replace([',', '.'], '', $service['total']) :
                    $service['total'];
                $total += $cleanTotal;
            } else {
                // Fallback: tính từ price * quantity
                $cleanPrice = is_string($service['price']) ?
                    (int)str_replace([',', '.'], '', $service['price']) :
                    $service['price'];
                $total += $cleanPrice * $service['quantity'];
            }
        }
        return $total;
    }

    /**
     * Update files
     *
     * @param Proposal $proposal
     * @param array $files
     * @return void
     */
    private function updateFiles($proposal, $files)
    {
        if (!empty($files)) {
            // Filter out null/empty files
            $files = array_filter($files, function ($file) {
                return $file instanceof \Illuminate\Http\UploadedFile;
            });

            if (empty($files)) {
                return;
            }

            // Nếu không ở trạng thái yêu cầu làm lại, xóa file cũ như bình thường
            if ($proposal->status != ProposalStatusEnum::REJECTED_REDO) {
                // Xóa file cũ khi không phải trạng thái yêu cầu làm lại
                // if ($proposal->files()->count() > 0) {
                //     $paths = $proposal->files()->pluck('path');
                //     foreach ($paths as $path) {
                //         FileHelper::deleteFile($path);
                //     }
                //     $proposal->files()->delete();
                // }
            }

            $path = 'proposals/' . str_replace('/', '-', $proposal->code);
            foreach ($files as $file) {
                try {
                    $uploadedFile = FileHelper::uploadFile($file, $path);
                    
                    // Only create file record if upload was successful
                    if (isset($uploadedFile['success']) && $uploadedFile['success'] === true && !empty($uploadedFile['path'])) {
                        $proposal->files()->create([
                            'path' => $uploadedFile['path'],
                            'name' => $uploadedFile['filename'],
                            'extension' => $uploadedFile['extension'],
                            'proposal_id' => $proposal->id,
                        ]);
                    } else {
                        Log::warning('File upload failed for proposal ' . $proposal->id, [
                            'response' => $uploadedFile,
                            'file_name' => $file->getClientOriginalName()
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception during file upload for proposal ' . $proposal->id, [
                        'error' => $e->getMessage(),
                        'file_name' => $file->getClientOriginalName()
                    ]);
                }
            }

            // Nếu đang ở trạng thái yêu cầu làm lại và đã upload file mới, chuyển sang đã chỉnh sửa
            if ($proposal->status == ProposalStatusEnum::REJECTED_REDO) {
                $proposal->update(['status' => ProposalStatusEnum::REVISED]);
            }
        }
    }

    /**
     * Download category files as zip
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function downloadFiles($id)
    {
        try {
            $proposal = $this->proposalRepository->find($id);

            if (!$proposal) {
                throw new \Exception('Không tìm thấy báo giá');
            }

            // Get all file paths for this category
            $filePaths = $proposal->files()->pluck('path')->toArray();

            if (empty($filePaths)) {
                throw new \Exception('Không có file nào để download');
            }

            // Create zip file (clean category code for filename)
            $cleanCode = str_replace(['/', '\\'], '-', $proposal->code);
            $zipName = 'proposal_' . $cleanCode . '_files';
            $result = FileHelper::createZipFromFiles($filePaths, $zipName, 'public');
            if (!$result['success']) {
                Log::error('Failed to create zip file for proposal: ' . $proposal->id, [
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
            Log::error('Error downloading proposal files', [
                'proposal_id' => $proposal ? $proposal->id : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi download files: ' . $e->getMessage());
        }
    }

    /**
     * Download a single file from a proposal.
     *
     * @param int $id
     * @param int $fileId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile($id, $fileId)
    {
        try {
            $proposal = $this->proposalRepository->find($id);

            if (!$proposal) {
                throw new \Exception('Không tìm thấy báo giá');
            }

            // Find the file
            $file = $proposal->files()->find($fileId);

            if (!$file) {
                throw new \Exception('Không tìm thấy file');
            }

            // Get the full file path
            $filePath = storage_path('app/public/' . $file->path);

            if (!file_exists($filePath)) {
                Log::error('File not found at path: ' . $filePath);
                throw new \Exception('File không tồn tại trên server');
            }

            // Get the file name
            $fileName = $file->name ?? basename($file->path);

            // Return the file download response
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('Error downloading proposal file', [
                'proposal_id' => $id,
                'file_id' => $fileId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi download file: ' . $e->getMessage());
        }
    }

    /**
     * Convert a proposal to an order.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function convertToOrder($id)
    {
        DB::beginTransaction();
        try {
            $proposal = $this->proposalRepository->find($id);
            if (!$proposal) {
                throw new \Exception('Không tìm thấy báo giá');
            }

            $update = $proposal->update([
                'status' => ProposalStatusEnum::CONVER_TO_ORDER,
            ]);

            if (!$update) {
                throw new \Exception('Không thể chuyển thành đơn hàng');
            }

            $this->handleConvertToOrder($proposal);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Convert a proposal to a contract.
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function convertToContract($id)
    {
        DB::beginTransaction();
        try {
            $proposal = $this->proposalRepository->find($id);
            if (!$proposal) {
                throw new \Exception('Không tìm thấy báo giá');
            }

            $update = $proposal->update([
                'status' => ProposalStatusEnum::CONVERT_TO_CONTRACT,
            ]);

            if (!$update) {
                throw new \Exception('Không thể chuyển thành hợp đồng');
            }

            $this->handleConvertToContract($proposal);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get a proposal by id.
     *
     * @param int $id
     * @return \App\Models\Proposal
     */
    public function getProposalById($id)
    {
        return $this->proposalRepository->getProposalById($id);
    }

    /**
     * Remove a file from a proposal.
     *
     * @param int $id
     * @param int $fileId
     * @return void
     */
    public function removeFile($id, $fileId)
    {
        $proposal = $this->proposalRepository->find($id);
        if (!$proposal) {
            throw new \Exception('Không tìm thấy báo giá');
        }

        $file = $proposal->files()->where('id', $fileId)->first();
        $deleted = $proposal->files()->where('id', $fileId)->delete();

        if ($deleted) {
            if (FileHelper::fileExists($file->path)) {
                FileHelper::deleteFile($file->path);
            }
        }
    }

    /**
     * Update a proposal.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateProposal($id, $data)
    {

        DB::beginTransaction();
        try {
            $proposal = $this->proposalRepository->findOrFail($id);
            $data['amount'] = $this->calculateAmount($data['services'] ?? []);

            $proposal->update($data);
            if (isset($data['files'])) {
                $this->updateFiles($proposal, $data['files']);
            }
            if (isset($data['services'])) {
                $proposal->services()->delete();
                foreach ($data['services'] as $service) {
                    $proposal->services()->create([
                        'category_id' => $service['category_id'] ?? null,
                        'service_id' => $service['service_id'] ?? null,
                        'product_id' => $service['product_id'] ?? null,
                        'price' => $service['price'],
                        'quantity' => $service['quantity'],
                        'total' => $service['total'] ?? ($service['price'] * $service['quantity']),
                        'proposal_id' => $proposal->id,
                    ]);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get categories for create form
     */
    public function getCategoriesForCreate()
    {
        return $this->categoryRepository->getActiveCategories();
    }

    public function handleConvertToOrder($proposal)
    {
        $data = [
            'status' => SellContractStatusEnum::NEW,
            'note' => $proposal->note ?? '',
            'expired_at' => $proposal->expired_at ?? now()->addDays(30),
            'proposal_id' => $proposal->id,
            'source_type' => 'proposal',
            'source_id' => $proposal->id,
            'sell_contract_id' => null,
            'customer_id' => $proposal->customer_id ?? null,
            'services' => $proposal->services?->map(function ($service) {
                return [
                    'category_id' => $service->category_id,
                    'service_id' => $service->service_id,
                    'product_id' => $service->product_id,
                    'price' => $service->price,
                    'quantity' => $service->quantity,
                    'total' => $service->total,
                ];
            })->toArray() ?? [],
            'files' => $proposal->files?->toArray() ?? [],
        ];

        $isConvertToOrder = true;
        $this->sellOrderService->createSellOrder($data, $isConvertToOrder);
    }

    /**
     * Handle converting proposal to contract.
     *
     * @param Proposal $proposal
     * @return void
     * @throws \Exception
     */
    public function handleConvertToContract($proposal)
    {
        $data = [
            'status' => SellContractStatusEnum::NEW,
            'note' => $proposal->note ?? '',
            'expired_at' => $proposal->expired_at ?? now()->addDays(30),
            'proposal_id' => $proposal->id,
            'customer_id' => $proposal->customer_id ?? null,
            'services' => $proposal->services?->map(function ($service) {
                return [
                    'category_id' => $service->category_id,
                    'service_id' => $service->service_id,
                    'product_id' => $service->product_id,
                    'price' => $service->price,
                    'quantity' => $service->quantity,
                    'total' => $service->total,
                ];
            })->toArray() ?? [],
            'files' => $proposal->files?->toArray() ?? [],
        ];

        $this->sellContractService->createSellContract($data);
    }

    /**
     * Mark proposal as rejected and request redo.
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function rejectRedo($id)
    {
        DB::beginTransaction();
        try {
            $proposal = $this->proposalRepository->find($id);
            if (!$proposal) {
                throw new \Exception('Không tìm thấy báo giá');
            }

            if ($proposal->status == ProposalStatusEnum::CONVER_TO_ORDER) {
                throw new \Exception('Báo giá đã chuyển thành đơn hàng, không thể yêu cầu làm lại');
            }

            $updated = $proposal->update([
                'status' => ProposalStatusEnum::REJECTED_REDO,
            ]);

            if (!$updated) {
                throw new \Exception('Không thể cập nhật trạng thái làm lại');
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
