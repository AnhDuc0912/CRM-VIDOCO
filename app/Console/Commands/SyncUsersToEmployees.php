<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\User\Models\User;
use Modules\Employee\Models\Employee;
use Modules\Core\Enums\TemplateCodeEnum;

class SyncUsersToEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:users-to-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đồng bộ users từ bảng users sang bảng employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu đồng bộ users...');
        
        $users = User::whereNull('employee_id')->get();
        
        if ($users->isEmpty()) {
            $this->info('Không có user nào cần đồng bộ.');
            return 0;
        }
        
        $this->info("Tìm thấy {$users->count()} user(s) chưa có employee_id");
        
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        
        foreach ($users as $user) {
            try {
                // Tách tên thành first_name và last_name
                $nameParts = explode(' ', trim($user->name), 2);
                $firstName = $nameParts[0] ?? $user->name;
                $lastName = $nameParts[1] ?? '';
                
                // Tạo employee mới
                $employee = Employee::create([
                    'code' => generate_code(TemplateCodeEnum::EMPLOYEE, 'employees'),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'full_name' => $user->name,
                    'email_work' => $user->email,
                    'phone' => '0000000000', // Placeholder, cần update sau
                    'created_by' => 1,
                ]);
                
                // Cập nhật user với employee_id
                $user->update(['employee_id' => $employee->id]);
                
                $this->newLine();
                $this->info("✓ Đã tạo employee cho user: {$user->name} (ID: {$employee->id})");
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("✗ Lỗi khi xử lý user {$user->name}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Hoàn tất đồng bộ!');
        
        return 0;
    }
}
