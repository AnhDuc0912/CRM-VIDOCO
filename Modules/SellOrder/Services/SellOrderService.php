<?php

namespace Modules\SellOrder\Services;

use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\SellOrder\Repositories\Contracts\SellOrderRepositoryInterface;

class SellOrderService
{
    protected $sellOrderRepository;

    public function __construct(SellOrderRepositoryInterface $sellOrderRepository)
    {
        $this->sellOrderRepository = $sellOrderRepository;
    }

    public function getSellOrders()
    {
        return $this->sellOrderRepository->getSellOrders();
    }

    public function getSellOrderById($id)
    {
        return $this->sellOrderRepository->getSellOrderById($id);
    }

    public function createSellOrder($data, $isConvertToOrder = false)
    {
        // Extract services and files from data
        $services = $data['services'] ?? [];
        $files = $data['files'] ?? [];
        // Filter only the fields needed for creating sell order
        $orderData = [
            'code' => generate_code(TemplateCodeEnum::SELL_ORDER, 'sell_orders'),
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
            $sellOrder = $this->sellOrderRepository->create($orderData);

            if (!empty($files)) {
                $this->updateFiles($sellOrder, $files, $isConvertToOrder);
            }

            if (!empty($services)) {
                foreach ($services as $service) {
                    $sellOrder->services()->create([
                        'category_id' => $service['category_id'],
                        'service_id' => $service['service_id'],
                        'product_id' => $service['product_id'],
                        'price' => $service['price'],
                        'total' => $service['total'],
                        'quantity' => $service['quantity'],
                        'sell_order_id' => $sellOrder->id,
                    ]);
                }
            }

            DB::commit();
            return $sellOrder;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Calculate amount of sell order services
     *
     * @param array $sellOrderServices
     * @return float
     */
    public function calculateAmount($sellOrderServices)
    {
        $total = 0;
        foreach ($sellOrderServices as $service) {
            $total += $service['price'] * $service['quantity'];
        }
        return $total;
    }

    /**
     * Update files
     *
     * @param SellOrder $sellOrder
     * @param array $files
     * @return void
     */
    private function updateFiles($sellOrder, $files, $isConvertToOrder = false)
    {
        if (!empty($files)) {
            $path = 'sell-orders/' . str_replace('/', '-', $sellOrder->code);
            foreach ($files as $file) {
                if (!$isConvertToOrder) {
                    $file = FileHelper::uploadFile($file, $path);
                } else {
                    FileHelper::copyFile($file['path'], $path . '/' . $file['name']);
                }
                $sellOrder->files()->create([
                    'path' => $isConvertToOrder ? $path . '/' . $file['name'] : $file['path'],
                    'name' => $isConvertToOrder ? $file['name'] : $file['filename'],
                    'extension' => $isConvertToOrder ? $file['extension'] : $file['extension'],
                    'sell_order_id' => $sellOrder->id,
                ]);
            }
        }
    }

    /**
     * Remove a file from a sell order.
     *
     * @param int $id
     * @param int $fileId
     * @return void
     */
    public function removeFile($id, $fileId)
    {
        $sellOrder = $this->sellOrderRepository->find($id);
        if (!$sellOrder) {
            throw new \Exception('Không tìm thấy đơn hàng');
        }

        $file = $sellOrder->files()->where('id', $fileId)->first();
        $deleted = $sellOrder->files()->where('id', $fileId)->delete();

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
    public function updateSellOrder($id, $data)
    {

        DB::beginTransaction();
        try {
            $sellOrder = $this->sellOrderRepository->findOrFail($id);
            $data['amount'] = $this->calculateAmount($data['services'] ?? []);
            $sellOrder->update($data);
            if (isset($data['files'])) {
                $this->updateFiles($sellOrder, $data['files']);
            }
            if (isset($data['services'])) {
                $sellOrder->services()->delete();
                foreach ($data['services'] as $service) {
                    $sellOrder->services()->create([
                        'category_id' => $service['category_id'],
                        'service_id' => $service['service_id'],
                        'product_id' => $service['product_id'],
                        'total' => $service['total'],
                        'price' => $service['price'],
                        'quantity' => $service['quantity'],
                        'sell_order_id' => $sellOrder->id,
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
            $sellOrder = $this->sellOrderRepository->find($id);

            if (!$sellOrder) {
                throw new \Exception('Không tìm thấy đơn hàng');
            }

            // Get all file paths for this category
            $filePaths = $sellOrder->files()->pluck('path')->toArray();

            if (empty($filePaths)) {
                throw new \Exception('Không có file nào để download');
            }

            // Create zip file (clean category code for filename)
            $cleanCode = str_replace(['/', '\\'], '-', $sellOrder->code);
            $zipName = 'sell_order_' . $cleanCode . '_files';
            $result = FileHelper::createZipFromFiles($filePaths, $zipName, 'public');
            if (!$result['success']) {
                Log::error('Failed to create zip file for sell order: ' . $sellOrder->id, [
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
            Log::error('Error downloading sell order files', [
                'sell_order_id' => $sellOrder ? $sellOrder->id : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi download files: ' . $e->getMessage());
        }
    }
}
