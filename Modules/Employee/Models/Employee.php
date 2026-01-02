<?php

namespace Modules\Employee\Models;

use Modules\Department\Models\Department;
use Modules\Employee\Models\EmployeeAllowance;
use Modules\Employee\Models\EmployeeBenefit;
use Modules\Employee\Models\EmployeeContract;
use Modules\Employee\Models\EmployeeDependent;
use Modules\Employee\Models\EmployeeSalary;
use Modules\Employee\Models\EmployeeBankAccount;
use Modules\Employee\Models\EmployeeFile;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUpdatedBy;
use Modules\Employee\Enums\EmployeeFileTypeEnum;
use Modules\Level\Models\Level;
use Modules\Position\Models\Position;

class Employee extends Model
{
    use CreatedUpdatedBy;

    protected $table = 'employees';
    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'code',
        'qr_code',
        'email_work',
        'email_personal',
        'password_setup_token',
        'password_setup_expires_at',
        'avatar',
        'level',
        'citizen_id_number',
        'citizen_id_created_date',
        'citizen_id_created_place',
        'education',
        'phone',
        'permanent_address',
        'current_address',
        'gender',
        'birthday',
        'current_position',
        'last_position',
        'start_date',
        'department_id',
        'manager_id',
        'updated_by',
        'created_by',
    ];

    protected $casts = [
        'birthday' => 'date',
        'contract_end_date' => 'date',
        'start_date' => 'date',
        'password_setup_expires_at' => 'datetime',
        'level' => 'integer',
        'current_position' => 'integer',
        'last_position' => 'integer',
    ];

    /**
     * Get the user that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'id');
    }

    /**
     * Get the department that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Get the bankAccount that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankAccount()
    {
        return $this->hasOne(EmployeeBankAccount::class, 'employee_id', 'id');
    }

    /**
     * Get the dependents for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dependents()
    {
        return $this->hasMany(EmployeeDependent::class, 'employee_id', 'id');
    }

    /**
     * Get the contracts for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts()
    {
        return $this->hasMany(EmployeeContract::class, 'employee_id', 'id');
    }

    /**
     * Get the salary for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function salary()
    {
        return $this->hasOne(EmployeeSalary::class, 'employee_id', 'id');
    }

    /**
     * Get the manager for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'id');
    }

    /**
     * Get the allowances for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allowances()
    {
        return $this->hasMany(EmployeeAllowance::class, 'employee_id', 'id');
    }

    /**
     * Get the benefits for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function benefits()
    {
        return $this->hasMany(EmployeeBenefit::class, 'employee_id', 'id');
    }

    /**
     * Get the files for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(EmployeeFile::class, 'employee_id', 'id');
    }

    public function getAvatarAttribute()
    {
        return $this->files?->where('type', EmployeeFileTypeEnum::AVATAR)->first()?->path ?? '';
    }

     public function position()
    {
        return $this->belongsTo(Position::class, 'current_position', 'id');
    }

     public function level_company()
    {
        return $this->belongsTo(Level::class, 'level', 'id');
    }
}
