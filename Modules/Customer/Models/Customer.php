<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Models\CustomerBehaviors;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderService;
use Modules\Category\Models\CategoryService;
use Modules\Employee\Models\Employee;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'customer_type',
        'source_customer',
        'person_incharge',
        'sales_person',
        'company_name',
        'tax_code',
        'founding_date',
        'company_address',
        'salutation',
        'last_name',
        'first_name',
        'birthday',
        'identity_card',
        'gender',
        'address',
        'phone',
        'sub_phone',
        'email',
        'sub_email',
        'facebook',
        'zalo',
        'note',
        'invoice_name',
        'invoice_tax_code',
        'invoice_email',
        'updated_by',
        'created_by',
    ];

    protected $casts = [];

    /**
     * Get the behaviors for the customer.
     */
    public function behaviors()
    {
        return $this->hasOne(CustomerBehaviors::class, 'customer_id', 'id');
    }

    /**
     * Get the bank accounts for the customer.
     */
    public function bankAccounts()
    {
        return $this->hasOne(CustomerBankAccount::class, 'customer_id', 'id');
    }

    /**
     * Get the orders for the customer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function services()
    {
        return $this->hasManyThrough(CategoryService::class, OrderService::class, 'order_id', 'id', 'id', 'service_id');
    }

    public function files()
    {
        return $this->hasMany(CustomerFile::class, 'customer_id', 'id');
    }

    public function personInCharge()
    {
        return $this->belongsTo(Employee::class, 'person_incharge', 'id');
    }
}
