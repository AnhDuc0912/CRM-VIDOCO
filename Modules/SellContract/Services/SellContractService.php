<?php

namespace Modules\SellContract\Services;

use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\SellContract\Enums\SellContractStatusEnum;
use Modules\SellContract\Repositories\Contracts\SellContractRepositoryInterface;
use Modules\SellOrder\Services\SellOrderService;

class SellContractService
{
    protected $sellContractRepository;
    protected $sellOrderService;

    public function __construct(SellContractRepositoryInterface $sellContractRepository, SellOrderService $sellOrderService)
    {
        $this->sellContractRepository = $sellContractRepository;
        $this->sellOrderService = $sellOrderService;
    }

    public function getSellContracts()
    {
        return $this->sellContractRepository->getSellContracts();
    }

    public function getSellContractById($id)
    {
        return $this->sellContractRepository->getSellContractById($id);
    }

    public function createSellContract($data)
    {
        // Extract services and files from data
        $services = $data['services'] ?? [];
        dd($services);
        $files = $data['files'] ?? [];

        // Filter only the fields needed for creating sell contract
        $contractData = [
            'code' => generate_code(TemplateCodeEnum::SELL_CONTRACT, 'sell_contracts'),
            'created_by' => Auth::user()->id,
            'proposal_id' => $data['proposal_id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'status' => $data['status'] ?? 1,
            'expired_at' => $data['expired_at'] ?? now()->addDays(30),
            'note' => $data['note'] ?? null,
            'amount' => $this->calculateAmount($services),
        ];


        DB::beginTransaction();
        try {
            $sellContract = $this->sellContractRepository->create($contractData);
            
            if (!empty($files)) {
                $this->updateFiles($sellContract, $files);
            }

            if (!empty($services)) {
                foreach ($services as $service) {
                    $sellContract->services()->create([
                        'category_id' => $service['category_id'],
                        'service_id' => $service['service_id'],
                        'product_id' => $service['product_id'],
                        'price' => $service['price'],
                        'total' => $service['total'],
                        'quantity' => $service['quantity'],
                        'sell_contract_id' => $sellContract->id,
                    ]);
                }
            }

            DB::commit();
            return $sellContract;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Calculate amount of sell contract services
     *
     * @param array $sellContractServices
     * @return float
     */
    public function calculateAmount($sellContractServices)
    {
        $total = 0;
        foreach ($sellContractServices as $service) {
            $total += $service['price'] * $service['quantity'];
        }
        return $total;
    }

    /**
     * Update files
     *
     * @param SellContract $sellContract
     * @param array $files
     * @return void
     */
    private function updateFiles($sellContract, $files)
    {
        if (!empty($files)) {
            $path = 'sell-contracts/' . str_replace('/', '-', $sellContract->code);
            foreach ($files as $file) {
                $file = FileHelper::uploadFile($file, $path);
                $sellContract->files()->create([
                    'path' => $file['path'] ?? '',
                    'name' => $file['filename'] ?? '',
                    'extension' => $file['extension'] ?? '',
                    'sell_contract_id' => $sellContract->id,
                ]);
            }
        }
    }

    /**
     * Remove a file from a sell contract.
     *
     * @param int $id
     * @param int $fileId
     * @return void
     */
    public function removeFile($id, $fileId)
    {
        $sellContract = $this->sellContractRepository->find($id);
        if (!$sellContract) {
            throw new \Exception('Không tìm thấy hợp đồng bán hàng');
        }

        $file = $sellContract->files()->where('id', $fileId)->first();
        $deleted = $sellContract->files()->where('id', $fileId)->delete();

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
    public function updateSellContract($id, $data)
    {

        DB::beginTransaction();
        try {
            $sellContract = $this->sellContractRepository->findOrFail($id);
            $data['amount'] = $this->calculateAmount($data['services'] ?? []);
            $sellContract->update($data);
            if (isset($data['files'])) {
                $this->updateFiles($sellContract, $data['files']);
            }
            if (isset($data['services'])) {
                $sellContract->services()->delete();
                foreach ($data['services'] as $service) {
                    $sellContract->services()->create([
                        'category_id' => $service['category_id'],
                        'service_id' => $service['service_id'],
                        'product_id' => $service['product_id'],
                        'total' => $service['total'],
                        'price' => $service['price'],
                        'quantity' => $service['quantity'],
                        'sell_contract_id' => $sellContract->id,
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
     * Download category files as zip
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function downloadFiles($id)
    {
        try {
            $sellContract = $this->sellContractRepository->find($id);

            if (!$sellContract) {
                throw new \Exception('Không tìm thấy hợp đồng bán hàng');
            }

            // Get all file paths for this category
            $filePaths = $sellContract->files()->pluck('path')->toArray();

            if (empty($filePaths)) {
                throw new \Exception('Không có file nào để download');
            }

            // Create zip file (clean category code for filename)
            $cleanCode = str_replace(['/', '\\'], '-', $sellContract->code);
            $zipName = 'sell_contract_' . $cleanCode . '_files';
            $result = FileHelper::createZipFromFiles($filePaths, $zipName, 'public');
            if (!$result['success']) {
                Log::error('Failed to create zip file for sell contract: ' . $sellContract->id, [
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
            Log::error('Error downloading sell contract files', [
                'sell_contract_id' => $sellContract ? $sellContract->id : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi download files: ' . $e->getMessage());
        }
    }

    /**
     * Convert a sell contract to an order.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function convertToOrder($id)
    {
        DB::beginTransaction();
        try {
            $sellContract = $this->sellContractRepository->find($id)->load('services', 'files');
            if (!$sellContract) {
                throw new \Exception('Không tìm thấy hợp đồng bán hàng');
            }

            $update = $sellContract->update([
                'status' => SellContractStatusEnum::CONVER_TO_ORDER,
            ]);

            if (!$update) {
                throw new \Exception('Không thể chuyển thành đơn hàng');
            }

            $this->handleConvertToOrder($sellContract);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function handleConvertToOrder($sellContract)
    {
        $data = [
            'status' => SellContractStatusEnum::NEW,
            'note' => $sellContract->note ?? '',
            'expired_at' => $sellContract->expired_at ?? now()->addDays(30),
            'proposal_id' => $sellContract->proposal_id,
            'source_type' => 'contract',
            'source_id' => $sellContract->id,
            'customer_id' => $sellContract->customer_id ?? null,
            'services' => $sellContract->services?->map(function ($service) {
                return [
                    'category_id' => $service->category_id,
                    'service_id' => $service->service_id,
                    'product_id' => $service->product_id,
                    'price' => $service->price,
                    'quantity' => $service->quantity,
                    'total' => $service->total,
                ];
            })->toArray() ?? [],
            'files' => $sellContract->files?->toArray() ?? [],
        ];

        $isConvertToOrder = true;
        $this->sellOrderService->createSellOrder($data, $isConvertToOrder);
    }
}
