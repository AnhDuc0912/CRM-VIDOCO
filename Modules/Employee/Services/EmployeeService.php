<?php

namespace Modules\Employee\Services;

use App\Helpers\FileHelper;
use Modules\Core\Enums\RoleEnum;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Employee\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Core\Enums\PermissionEnum;
use Modules\Employee\Enums\EmployeeFileTypeEnum;
use Modules\Employee\Mail\SendPasswordSetupMail;
use Modules\Employee\Repositories\Contracts\EmployeeRepositoryInterface;
use Modules\User\Models\User;

class EmployeeService
{
    protected EmployeeRepositoryInterface $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Get all employees
     *
     * @return Collection
     */
    public function getAllEmployees()
    {
        $employees = $this->employeeRepository->getAllEmployees();

        /** @var \Modules\User\Models\User $user */
        $user = Auth::user();

        if ($user && $user->hasRole(RoleEnum::EMPLOYEE)) {
            $employees = $employees->filter(function ($employee) use ($user) {
                return $employee->user?->id === $user->id;
            });
        }

        return $employees;
    }

    /**
     * Get all employees by department
     *
     * @param string $departmentName
     * @return Collection
     */
    public function getEmployeesByDepartment($departmentName)
    {
        $employees = $this->employeeRepository->getAllEmployees()
            ->filter(function ($employee) use ($departmentName) {
                return $employee->department?->name === $departmentName;
            });

        /** @var \Modules\User\Models\User $user */
        $user = Auth::user();

        if ($user && $user->hasRole(RoleEnum::EMPLOYEE)) {
            $employees = $employees->filter(function ($employee) use ($user) {
                return $employee->user?->id === $user->id;
            });
        }

        return $employees;
    }

    /**
     * Get all employees by position
     *
     * @param string $positionName
     * @return Collection
     */
    public function getEmployeesByPosition($positionName)
    {
        // Get position by name
        $position = \Modules\Position\Models\Position::where('name', $positionName)->first();
        
        if (!$position) {
            return collect([]);
        }

        $employees = $this->employeeRepository->getAllEmployees()
            ->filter(function ($employee) use ($position) {
                return $employee->current_position === $position->id;
            });

        /** @var \Modules\User\Models\User $user */
        $user = Auth::user();

        if ($user && $user->hasRole(RoleEnum::EMPLOYEE)) {
            $employees = $employees->filter(function ($employee) use ($user) {
                return $employee->user?->id === $user->id;
            });
        }

        return $employees;
    }

    /**
     * Get employee by id
     *
     * @param int $id
     * @return Employee
     */
    public function getEmployeeById($id)
    {
        return $this->employeeRepository->getEmployeeById($id);
    }

    /**
     * Update password for the specified resource.
     *
     * @param int $userId
     * @param array $data
     * @return void
     */
    public function updatePassword($userId, $data)
    {
        return $this->employeeRepository->updatePassword($userId, $data);
    }

    /**
     * Get all managers
     *
     * @return Collection
     */
    public function getAllManagers()
    {
        return $this->employeeRepository->getAllManagers();
    }

    /**
     * Get next employee code
     *
     * @return string
     */
    public function getNextEmployeeCode()
    {
        $lastEmployee = $this->employeeRepository->getLastEmployee();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;

        return TemplateCodeEnum::EMPLOYEE . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Update employee and contract
     *
     * @param int $id
     * @param array $data
     * @return Employee
     */
    public function updateEmployee($id, $data)
    {
        $employee = $this->employeeRepository->getEmployeeById($id);
        $uploadedFiles = array_filter($data['files'] ?? [], function ($item) {
            return !is_null($item);
        });

        DB::beginTransaction();
        try {
            $this->updateProfileAndJob($employee, $data);
            $this->updateFiles($employee, $uploadedFiles);
            $this->syncDependents($employee, $data['dependent'] ?? []);
            $this->updateBankAccount($employee, $data['bank_account'] ?? []);
            $this->updateOrCreateContract($employee, $data['contract'] ?? []);
            $this->updateSalary($employee, $data['salary'] ?? []);
            $this->syncAllowances($employee, $data['allowance'] ?? []);
            $this->syncBenefits($employee, $data['benefit'] ?? []);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $employee;
    }

    /**
     * Store employee and related data
     *
     * @param array $data
     * @return Employee
     */
    public function storeEmployee($data)
    {
        DB::beginTransaction();
        $uploadedFiles = array_filter($data['files'] ?? [], function ($item) {
            return !is_null($item);
        });

        try {
            $employee = $this->createProfileAndJob($data);
            $this->updateFiles($employee, $uploadedFiles);
            $this->updateBankAccount($employee, $data['bank_account'] ?? []);
            $this->updateOrCreateContract($employee, $data['contract'] ?? []);
            $this->updateSalary($employee, $data['salary'] ?? []);
            $this->syncDependents($employee, $data['dependent'] ?? []);
            $this->syncAllowances($employee, $data['allowance'] ?? []);
            $this->syncBenefits($employee, $data['benefit'] ?? []);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $employee;
    }

    /**
     * Update profile and job
     *
     * @param Employee $employee
     * @param array $data
     * @param string $action 'create' or 'update'
     * @return void
     */
    private function updateProfileAndJob($employee, $data)
    {
        if (!empty($data['profile']) && !empty($data['job'])) {
            $profile = $data['profile'];
            $job = $data['job'];
            $employee->update([
                'first_name' => $profile['first_name'],
                'last_name' => $profile['last_name'],
                'full_name' => $profile['first_name'] . ' ' . $profile['last_name'],
                'birthday' => $profile['birthday'],
                'gender' => $profile['gender'],
                'citizen_id_number' => $profile['citizen_id_number'],
                'citizen_id_created_date' => $profile['citizen_id_created_date'] ?? null,
                'citizen_id_created_place' => $profile['citizen_id_created_place'] ?? null,
                'phone' => $profile['phone'],
                'email_work' => $profile['email_work'],
                'email_personal' => $profile['email_personal'] ?? null,
                'current_address' => $profile['current_address'] ?? null,
                'permanent_address' => $profile['permanent_address'] ?? null,
                'level' => $job['level'] ?? null,
                'current_position' => $job['current_position'],
                'last_position' => $job['last_position'] ?? null,
                'start_date' => $job['start_date'],
                'department_id' => $job['department_id'],
                'manager_id' => $job['manager_id'] ?? null,
                'updated_by' => Auth::user()->id,
            ]);
        }
    }

    /**
     * Create profile and job
     *
     * @param array $data
     * @return Employee
     */
    private function createProfileAndJob($data)
    {
        $profile = $data['profile'];
        $job = $data['job'];
        return $this->employeeRepository->create([
            'first_name' => $profile['first_name'],
            'last_name' => $profile['last_name'],
            'full_name' => $profile['first_name'] . ' ' . $profile['last_name'],
            'birthday' => $profile['birthday'],
            'gender' => $profile['gender'],
            'citizen_id_number' => $profile['citizen_id_number'],
            'citizen_id_created_date' => $profile['citizen_id_created_date'] ?? null,
            'citizen_id_created_place' => $profile['citizen_id_created_place'] ?? null,
            'phone' => $profile['phone'],
            'email_work' => $profile['email_work'],
            'email_personal' => $profile['email_personal'] ?? null,
            'current_address' => $profile['current_address'] ?? null,
            'permanent_address' => $profile['permanent_address'] ?? null,
            'code' => $this->getNextEmployeeCode(),
            'qr_code' => $this->getNextEmployeeCode(),
            'level' => $job['level'] ?? null,
            'current_position' => $job['current_position'],
            'last_position' => $job['last_position'] ?? null,
            'start_date' => $job['start_date'],
            'department_id' => $job['department_id'],
            'manager_id' => $job['manager_id'] ?? null,
            'created_by' => Auth::user()->id,
        ]);
    }

    /**
     * Sync dependents
     *
     * @param Employee $employee
     * @param array $dependents
     * @return void
     */
    private function syncDependents($employee, $dependents)
    {
        $employee->dependents()->delete();
        foreach ($dependents as $dependent) {
            $employee->dependents()->create($dependent);
        }
    }

    /**
     * Update bank account
     *
     * @param Employee $employee
     * @param array $bank
     * @return void
     */
    private function updateBankAccount($employee, $bank)
    {
        if (!empty($bank)) {
            $employee->bankAccount()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'bank_account_number' => $bank['bank_account_number'],
                    'bank_account_name' => $bank['bank_account_name'],
                    'bank_branch' => $bank['bank_branch'] ?? null,
                    'bank_name' => $bank['bank_name'],
                ]
            );
        }
    }

    /**
     * Update or create contract
     *
     * @param Employee $employee
     * @param array $contract
     * @return void
     */
    private function updateOrCreateContract($employee, $contract)
    {
        if (!empty($contract)) {
            $contractData = [
                'contract_type' => $contract['contract_type'],
                'start_date' => $contract['start_date'],
                'end_date' => $contract['end_date'] ?? null,
                'status' => 1,
                'note' => $contract['note'] ?? null,
            ];
            $latestContract = $employee->contracts()->latest()->first();
            if ($latestContract) {
                $latestContract->update($contractData);
            } else {
                $employee->contracts()->create($contractData);
            }
        }
    }

    /**
     * Update salary
     *
     * @param Employee $employee
     * @param array $salary
     * @return void
     */
    private function updateSalary($employee, $salary)
    {
        if (!empty($salary)) {
            $employee->salary()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'base_salary' => $salary['base_salary'] ?? null,
                    'basic_salary' => $salary['basic_salary'] ?? null,
                    'insurance_salary' => $salary['insurance_salary'] ?? null,
                ]
            );
        }
    }

    /**
     * Update files
     *
     * @param Employee $employee
     * @param array $files
     * @return void
     */
    private function updateFiles($employee, $files)
    {
        if (!empty($files)) {
            $path = 'employees/' . str_replace('/', '-', $employee->code);
            foreach ($files as $key => $file) {
                if ($key == EmployeeFileTypeEnum::OTHER_LABEL) {
                    foreach ($file as $fileItem) {
                        $fileUpload = FileHelper::uploadFile($fileItem, $path);

                        $employee->files()->create([
                            'path' => $fileUpload['path'],
                            'name' => $fileUpload['filename'],
                            'extension' => $fileUpload['extension'],
                            'type' => EmployeeFileTypeEnum::getTypeByLabel($key),
                        ]);
                    }
                } else {
                    $checkFileExist = $employee->files()->where('type', EmployeeFileTypeEnum::getTypeByLabel($key))->first();
                    if ($checkFileExist && EmployeeFileTypeEnum::getTypeByLabel($key) != EmployeeFileTypeEnum::OTHER) {
                        if (FileHelper::fileExists($checkFileExist->path)) {
                            FileHelper::deleteFile($checkFileExist->path);
                        }
                        $checkFileExist->delete();
                    }
                    $fileUpload = FileHelper::uploadFile($file, $path);

                    $employee->files()->create([
                        'path' => $fileUpload['path'],
                        'name' => $fileUpload['filename'],
                        'extension' => $fileUpload['extension'],
                        'type' => EmployeeFileTypeEnum::getTypeByLabel($key),
                    ]);
                }
            }
        }
    }

    /**
     * Sync allowances
     *
     * @param Employee $employee
     * @param array $allowances
     * @return void
     */
    private function syncAllowances($employee, $allowances)
    {
        $employee->allowances()->delete();
        foreach ($allowances as $allowance) {
            $employee->allowances()->create($allowance);
        }
    }

    /**
     * Sync benefits
     *
     * @param Employee $employee
     * @param array $benefits
     * @return void
     */
    private function syncBenefits($employee, $benefits)
    {
        $employee->benefits()->delete();
        foreach ($benefits as $benefit) {
            $employee->benefits()->create($benefit);
        }
    }

    /**
     * Update employee status
     *
     * @param int $employeeId
     * @return void
     */
    public function updateStatus($employeeId, $status)
    {
        $employee = $this->employeeRepository->getEmployeeById($employeeId);

        $updateStatus = $employee->user()->update([
            'status' => $status,
        ]);

        return $updateStatus;
    }

    /**
     * Delete employee
     *
     * @param int $id
     * @return void
     */
    public function deleteEmployee($id)
    {
        DB::beginTransaction();
        try {
            $employee = $this->employeeRepository->getEmployeeById($id);
            $employee->user()->delete();
            $employee->files()->delete();
            $employee->dependents()->delete();
            $employee->allowances()->delete();
            $employee->benefits()->delete();
            $employee->contracts()->delete();
            $employee->salary()->delete();
            $employee->bankAccount()->delete();
            $employee->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Send password setup email to employee
     *
     * @param int $employeeId
     * @return bool
     */
    public function sendPasswordSetupEmail($employeeId)
    {
        $employee = $this->getEmployeeById($employeeId);
        $existsUser = DB::table('users')->where('email', $employee->email_work ?? $employee->email_personal)->first();

        if (!$employee || !empty($existsUser)) {
            return false;
        }

        // Generate token
        $token = Str::random(64);
        $expiresAt = now()->addHours(24);

        // Update employee with token
        $employee->update([
            'password_setup_token' => $token,
            'password_setup_expires_at' => $expiresAt,
        ]);

        // Generate setup URL
        $setupUrl = route('employees.setup-password-form', ['token' => $token]);

        // Send email
        Mail::to($employee->email_work)
            ->send(new SendPasswordSetupMail($employee, $setupUrl));

        return true;
    }

    /**
     * Get employee by setup token
     *
     * @param string $token
     * @return Employee|null
     */
    public function getEmployeeBySetupToken($token)
    {
        return Employee::where('password_setup_token', $token)
            ->where('password_setup_expires_at', '>', now())
            ->first();
    }

    /**
     * Setup password for employee
     *
     * @param string $token
     * @param string $password
     * @return bool
     */
    public function setupPassword($token, $password)
    {
        $employee = $this->getEmployeeBySetupToken($token);

        if (!$employee) {
            return false;
        }

        DB::transaction(function () use ($employee, $password) {
            // Create user account
            $user = User::create([
                'name' => $employee->full_name,
                'email' => $employee->email_work ?: $employee->email_personal,
                'password' => Hash::make($password),
                'employee_id' => $employee->id,
            ]);

            // Assign employee role
            $user->givePermissionTo(PermissionEnum::EMPLOYEE_VIEW, PermissionEnum::EMPLOYEE_SHOW);
            $user->assignRole(RoleEnum::EMPLOYEE);

            // Clear setup token
            $employee->update([
                'password_setup_token' => null,
                'password_setup_expires_at' => null,
            ]);
        });

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
        $employee = $this->employeeRepository->getEmployeeById($id);
        if (!$employee) {
            throw new \Exception('Không tìm thấy nhân sự');
        }

        $file = $employee->files()->where('id', $fileId)->first();
        $deleted = $employee->files()->where('id', $fileId)->delete();

        if ($deleted) {
            if (FileHelper::fileExists($file->path)) {
                FileHelper::deleteFile($file->path);
            }
        }
    }

    /**
     * Download employee files as zip
     *
     * @param int $employeeId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function downloadFiles($employeeId)
    {
        try {
            $employee = $this->employeeRepository->find($employeeId);

            if (!$employee) {
                throw new \Exception('Không tìm thấy nhân sự');
            }

            // Get all file paths for this category
            $filePaths = $employee->files()->where('type', EmployeeFileTypeEnum::OTHER)->pluck('path')->toArray();

            if (empty($filePaths)) {
                throw new \Exception('Không có file nào để download');
            }

            // Create zip file (clean category code for filename)
            $cleanCode = str_replace(['/', '\\'], '-', $employee->code);
            $zipName = 'employee_' . $cleanCode . '_files';
            $result = FileHelper::createZipFromFiles($filePaths, $zipName, 'public');
            if (!$result['success']) {
                Log::error('Failed to create zip file for employee: ' . $employee->id, [
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
            Log::error('Error downloading employee files', [
                'employee_id' => $employee ? $employee->id : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi download files: ' . $e->getMessage());
        }
    }
}
