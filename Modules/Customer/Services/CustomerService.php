<?php

namespace Modules\Customer\Services;

use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Modules\Core\Enums\PermissionEnum;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Customer\Repositories\Contracts\CustomerRepositoryInterface;
use Modules\Customer\Repositories\Contracts\CustomerBankAccountRepositoryInterface;
use Modules\Customer\Repositories\Contracts\CustomerBehaviorRepositoryInterface;
use Modules\Customer\Enums\CustomerFileTypeEnum;
use Modules\Customer\Enums\CustomerTypeEnum;
use Modules\SellContract\Models\SellContract;
use Modules\Employee\Models\Employee;

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
    public function getAllCustomers(?string $segment = null)
    {
        $customers = $this->customerRepository->getAllCustomers();

        $today = now();

        $customers->each(function ($customer) use ($today) {
            $orderServices = $customer->orders?->flatMap->orderServices ?? collect();
            $hasOrders = $orderServices->count() > 0;

            $activeCount = $orderServices->filter(function ($service) use ($today) {
                // Treat missing end_date as active
                if (is_null($service->end_date)) {
                    return true;
                }
                return $service->end_date >= $today;
            })->count();

            if ($activeCount > 0) {
                $customer->segment_tag = 'using';
                $customer->segment_label = 'Khách hàng đang sử dụng';
            } elseif ($hasOrders) {
                $customer->segment_tag = 'stopped';
                $customer->segment_label = 'Khách hàng ngừng sử dụng';
            } else {
                $customer->segment_tag = 'lead';
                $customer->segment_label = 'Khách liên hệ';
            }
        });

        if ($segment && $segment !== 'all') {
            $customers = $customers->filter(fn ($c) => $c->segment_tag === $segment)->values();
        }

        return $customers;
    }

    /**
     * Get customers visible to the given user
     * - If user has CUSTOMER_SHOW_ALL or CUSTOMER_VIEW: return all customers
     * - If user has CUSTOMER_INCHARGE only: return customers where user is person_incharge
     * - Else: return customers assigned to user (sales_person or person_incharge)
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param string|null $segment
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function getCustomersForUser($user, ?string $segment = null, array $filters = [])
    {
        $customers = $this->getAllCustomers($segment);

        // Full permissions: see all customers
        if ($user && (Gate::allows(PermissionEnum::CUSTOMER_SHOW_ALL) || Gate::allows(PermissionEnum::CUSTOMER_VIEW))) {
            return $this->applyCustomerFilters($customers, $filters);
        }

        // INCHARGE permission only: see only customers where user is person_incharge
        if ($user && Gate::allows(PermissionEnum::CUSTOMER_INCHARGE)) {
            $employeeId = $user->employee?->id ?? null;
            if (!$employeeId) {
                return collect();
            }

            $customers = $customers->filter(function ($c) use ($employeeId) {
                return $c->person_incharge === $employeeId;
            })->values();
            return $this->applyCustomerFilters($customers, $filters);
        }

        // Default: see customers as sales_person or person_incharge
        $employeeId = $user?->employee?->id ?? null;
        if (!$employeeId) {
            return collect();
        }

        $customers = $customers->filter(function ($c) use ($employeeId) {
            return ($c->sales_person === $employeeId) || ($c->person_incharge === $employeeId);
        })->values();

        return $this->applyCustomerFilters($customers, $filters);
    }

    /**
     * Apply optional filters on customers collection
     */
    private function applyCustomerFilters($customers, array $filters)
    {
        if (!empty($filters['sales_person_id'])) {
            $salesId = (int) $filters['sales_person_id'];
            $customers = $customers->filter(fn($c) => $c->sales_person === $salesId)->values();
        }

        if (!empty($filters['person_incharge_id'])) {
            $inchargeId = (int) $filters['person_incharge_id'];
            $customers = $customers->filter(fn($c) => $c->person_incharge === $inchargeId)->values();
        }

        return $customers;
    }

    /**
     * Business statistics for dashboard (respecting permissions and filters)
     */
    public function getBusinessStats($user, array $filters = []): array
    {
        $customers = $this->getCustomersForUser($user, null, $filters);
        $today = now();

        $totalCustomers = $customers->count();
        $personalCount = $customers->where('customer_type', CustomerTypeEnum::PERSONAL)->count();
        $companyCount = $customers->where('customer_type', CustomerTypeEnum::COMPANY)->count();

        $usingCount = $customers->where('segment_tag', 'using')->count();
        $leadCount = $customers->where('segment_tag', 'lead')->count();
        $stoppedCount = $customers->where('segment_tag', 'stopped')->count();

        // Số báo giá (proposals)
        $allProposals = $customers->flatMap->proposals;
        $proposalCount = $allProposals->count();

        // Số đơn hàng (orders)
        $allOrders = $customers->flatMap->orders;
        $orderCount = $allOrders->count();

        // Khách hàng đã mua (có đơn hàng)
        $customerCount = $usingCount + $stoppedCount;

        // Tổng dịch vụ đang sử dụng
        $serviceCount = $customers
            ->flatMap->orders
            ->flatMap->orderServices
            ->filter(function ($service) use ($today) {
                return is_null($service->end_date) || $service->end_date >= $today;
            })
            ->count();

        // Tổng doanh thu từ đơn hàng
        $totalRevenue = $customers
            ->flatMap->orders
            ->flatMap->orderServices
            ->sum('total_price');

        // Tỷ lệ chuyển đổi: báo giá -> đơn hàng
        $conversionRate = $proposalCount > 0 ? ($orderCount / $proposalCount * 100) : 0;

        // Thống kê theo nhân viên Sale (từ customer.sales_person_id)
        $statsByEmployee = $customers
            ->groupBy('sales_person_id')
            ->mapWithKeys(function ($customerGroup, $employeeId) {
                $proposals = $customerGroup->flatMap->proposals->count();
                $orders = $customerGroup->flatMap->orders->count();
                $contracts = SellContract::whereIn('customer_id', $customerGroup->pluck('id'))->count();
                
                $employee = Employee::find($employeeId);
                $employeeName = $employee ? ($employee->full_name ?? 'NV #' . $employeeId) : 'Không gán';

                return [$employeeId => [
                    'id' => $employeeId,
                    'name' => $employeeName,
                    'proposals' => $proposals,
                    'orders' => $orders,
                    'contracts' => $contracts,
                ]];
            })
            ->toArray();

        $monthlyNew = $customers
            ->filter(fn($c) => $c->created_at)
            ->groupBy(fn($c) => $c->created_at->format('Y-m'))
            ->map->count()
            ->sortKeys()
            ->toArray();

        $quarterlyNew = $customers
            ->filter(fn($c) => $c->created_at)
            ->groupBy(function ($c) {
                $year = $c->created_at->format('Y');
                $quarter = (int) ceil($c->created_at->format('n') / 3);
                return $year . '-Q' . $quarter;
            })
            ->map->count()
            ->sortKeys()
            ->toArray();

        $yearlyNew = $customers
            ->filter(fn($c) => $c->created_at)
            ->groupBy(fn($c) => $c->created_at->format('Y'))
            ->map->count()
            ->sortKeys()
            ->toArray();

        $customerRevenue = $customers->mapWithKeys(function ($c) {
            $revenue = $c->orders
                ->flatMap->orderServices
                ->sum('total_price');

            $displayName = $c->customer_type == CustomerTypeEnum::COMPANY
                ? ($c->company_name ?: $c->code)
                : trim(($c->last_name ?? '') . ' ' . ($c->first_name ?? ''));

            return [$c->id => [
                'id' => $c->id,
                'name' => $displayName ?: $c->code,
                'revenue' => $revenue,
            ]];
        });

        $topCustomers = $customerRevenue
            ->sortByDesc('revenue')
            ->take(10)
            ->values()
            ->toArray();

        return [
            'total_customers' => $totalCustomers,
            'personal_count' => $personalCount,
            'company_count' => $companyCount,
            'using_count' => $usingCount,
            'lead_count' => $leadCount,
            'stopped_count' => $stoppedCount,
            'customer_count' => $customerCount,
            'service_count' => $serviceCount,
            'monthly_new' => $monthlyNew,
            'quarterly_new' => $quarterlyNew,
            'yearly_new' => $yearlyNew,
            'total_revenue' => $totalRevenue,
            'top_customers' => $topCustomers,
            'conversion_rate' => $conversionRate,
            'stats_by_employee' => $statsByEmployee,
        ];
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

    /**
     * Transfer all customers from one employee to another
     * @param int $fromEmployeeId - employee ID to transfer FROM
     * @param int $toEmployeeId - employee ID to transfer TO
     * @param string $type - 'sales_person' or 'person_incharge'
     * @return int - number of customers transferred
     */
    public function transferCustomersByEmployee(int $fromEmployeeId, int $toEmployeeId, string $type = 'sales_person')
    {
        if ($fromEmployeeId === $toEmployeeId) {
            throw new \Exception('Nhân viên nguồn và đích không thể giống nhau');
        }

        DB::beginTransaction();
        try {
            $field = $type === 'person_incharge' ? 'person_incharge' : 'sales_person';
            
            $count = $this->customerRepository
                ->getModel()
                ->where($field, $fromEmployeeId)
                ->update([$field => $toEmployeeId]);

            DB::commit();
            return $count;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transfer customers error', [
                'from_employee_id' => $fromEmployeeId,
                'to_employee_id' => $toEmployeeId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
