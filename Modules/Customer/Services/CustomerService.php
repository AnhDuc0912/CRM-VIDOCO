<?php

namespace Modules\Customer\Services;

use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Customer\Repositories\Contracts\CustomerRepositoryInterface;
use Modules\Customer\Repositories\Contracts\CustomerBankAccountRepositoryInterface;
use Modules\Customer\Repositories\Contracts\CustomerBehaviorRepositoryInterface;
use Modules\Customer\Enums\CustomerFileTypeEnum;
use Modules\Customer\Enums\CustomerTypeEnum;

class CustomerService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
        protected CustomerBankAccountRepositoryInterface $bankAccountRepository,
        protected CustomerBehaviorRepositoryInterface $behaviorRepository
    ) {}

    /**
     * get all customers
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCustomers()
    {
        return $this->customerRepository->getAllCustomers();
    }

    /**
     * create customer
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createCustomer(array $data)
    {
        DB::beginTransaction();
        try {
            $dataCustomer = [];
            if (!empty($data['personal'])) {
                $dataCustomer = $data['personal'];
            }

            if (!empty($data['company'])) {
                $dataCustomer = $data['company'];
            }

            $dataCustomer['code'] = generate_code(TemplateCodeEnum::CUSTOMER, 'customers');
            $dataCustomer['customer_type'] = (int) $data['customer_type'];
            $dataBankAccount = $data['customer_type'] == CustomerTypeEnum::PERSONAL ? $data['bank']['personal'] : $data['bank']['company'];
            $dataCustomer['created_by'] = Auth::user()->id;
            $customer = $this->customerRepository->create($dataCustomer);
            $this->createBankAccount($dataBankAccount ?? [], $customer->id);
            $this->createBehavior(array_merge($data['behavior'] ?? [], $data['relationship'] ?? []), $customer->id);
            $this->updateFiles($customer, $data['files'] ?? []);
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * create bank account for customer
     *
     * @param array $data
     * @return mixed
     */
    private function createBankAccount(array $data, int $customerId)
    {
        if (empty($data)) {
            return;
        }

        $dataBank = [];
        foreach ($data as $key => $value) {
            $dataBank[$key] = $value;
        }
        $dataBank['customer_id'] = $customerId;
        return $this->bankAccountRepository->create($dataBank);
    }

    /**
     * create behavior for customer
     *
     * @param array $data
     * @return mixed
     */
    private function createBehavior(array $data, int $customerId)
    {
        if (empty($data)) {
            return;
        }

        $dataBehavior = [];
        foreach ($data as $key => $value) {
            $dataBehavior[$key] = $value;
        }

        $dataBehavior['customer_id'] = $customerId;
        return $this->behaviorRepository->create($dataBehavior);
    }

    /**
     * Update files
     *
     * @param Customer $customer
     * @param array $files
     * @return void
     */
    private function updateFiles($customer, $files)
    {
        if (!empty($files)) {
            // if ($customer->files()->count() > 0) {
            //     $paths = $customer->files()->pluck('path');
            //     foreach ($paths as $path) {
            //         FileHelper::deleteFile($path);
            //     }
            //     $customer->files()->delete();
            // }

            $path = 'customers/' . str_replace('/', '-', $customer->code);
            foreach ($files as $file) {
                $file = FileHelper::uploadFile($file, $path);
                $customer->files()->create([
                    'path' => $file['path'] ?? '',
                    'name' => $file['filename'] ?? '',
                    'extension' => $file['extension'] ?? '',
                    'customer_id' => $customer->id,
                ]);
            }
        }
    }

    /**
     * get customer by id
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getCustomerById(int $id)
    {
        return $this->customerRepository->getCustomerById($id);
    }

    /**
     * update customer
     *
     * @param int $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateCustomer(int $id, array $data)
    {

        DB::beginTransaction();
        try {
            $dataCustomer = [];
            if (!empty($data['personal'])) {
                $dataCustomer = $data['personal'];
            }

            if (!empty($data['company'])) {
                $dataCustomer = $data['company'];
            }

            $dataCustomer['customer_type'] = (int) $data['customer_type'];
            $dataBankAccount = $data['customer_type'] == CustomerTypeEnum::PERSONAL ? $data['bank']['personal'] : $data['bank']['company'];
            $dataCustomer['updated_by'] = Auth::user()->id;
            $customer = $this->customerRepository->update($id, $dataCustomer);
            $this->updateBankAccount($dataBankAccount ?? [], $id);

            $this->updateBehavior(array_merge($data['behavior'] ?? [], $data['relationship'] ?? []), $id);
            $this->updateFiles($customer, $data['files'] ?? []);
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * update bank account for customer
     *
     * @param array $data
     * @return mixed
     */
    private function updateBankAccount(array $data, int $customerId)
    {
        if (empty($data)) {
            return;
        }

        $dataBank = [];
        foreach ($data as $key => $value) {
            $dataBank[$key] = $value;
        }

        $dataBank['customer_id'] = $customerId;
        $customer = $this->customerRepository->find($customerId);
        $customer->bankAccounts()->delete();
        return $customer->bankAccounts()->create($dataBank);
    }

    /**
     * update behavior for customer
     *
     * @param array $data
     * @return mixed
     */
    private function updateBehavior(array $data, int $customerId)
    {
        if (empty($data)) {
            return;
        }

        $dataBehavior = [];
        foreach ($data as $key => $value) {
            $dataBehavior[$key] = $value;
        }

        $dataBehavior['customer_id'] = $customerId;
        $customer = $this->customerRepository->findOrFail($customerId);
        $customer->behaviors()->delete();
        return $customer->behaviors()->create($dataBehavior);
    }

    /**
     * delete customer
     *
     * @param int $id
     * @return bool
     */
    public function deleteCustomer(int $id)
    {
        DB::beginTransaction();
        try {
            $customer = $this->customerRepository->findOrFail($id);
            $customer->behaviors()->delete();
            $customer->bankAccounts()->delete();
            $customer->files()->delete();
            $this->customerRepository->delete($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return true;
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
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            throw new \Exception('Không tìm thấy khách hàng');
        }

        $file = $customer->files()->where('id', $fileId)->first();
        $deleted = $customer->files()->where('id', $fileId)->delete();

        if ($deleted) {
            if (FileHelper::fileExists($file->path)) {
                FileHelper::deleteFile($file->path);
            }
        }
    }

    /**
     * Download customer files as zip
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function downloadFiles($id)
    {
        try {
            $customer = $this->customerRepository->find($id);

            if (!$customer) {
                throw new \Exception('Không tìm thấy khách hàng');
            }

            // Get all file paths for this customer
            $filePaths = $customer->files()->pluck('path')->toArray();

            if (empty($filePaths)) {
                throw new \Exception('Không có file nào để download');
            }

            // Create zip file (clean customer code for filename)
            $cleanCode = str_replace(['/', '\\'], '-', $customer->code);
            $zipName = 'customer_' . $cleanCode . '_files';
            $result = FileHelper::createZipFromFiles($filePaths, $zipName, 'public');
            if (!$result['success']) {
                Log::error('Failed to create zip file for customer: ' . $customer->id, [
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
            Log::error('Error downloading customer files', [
                'customer_id' => $customer ? $customer->id : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi download files: ' . $e->getMessage());
        }
    }
}
