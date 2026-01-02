<?php

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Customer\Enums\CustomerTypeEnum;

class UpdateOrCreateCustomerRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $filteredBehavior = array_filter($this->behavior ?? [], function ($value) {
            return !is_null($value) && $value !== '';
        });

        $filteredBankPersonal = array_filter($this->bank?->personal ?? [], function ($value) {
            return !is_null($value) && $value !== '';
        });
        $filteredBankCompany = array_filter($this->bank?->company ?? [], function ($value) {
            return !is_null($value) && $value !== '';
        });

        $filteredRelationship = array_filter($this->relationship ?? [], function ($value) {
            return !is_null($value) && $value !== '';
        });

        $this->merge([
            'personal' => $this->customer_type == CustomerTypeEnum::PERSONAL ? $this->personal : [],
            'company' => $this->customer_type == CustomerTypeEnum::COMPANY ? $this->company : [],
            'bank_personal' => $filteredBankPersonal,
            'bank_company' => $filteredBankCompany,
            'behavior' => $filteredBehavior,
            'relationship' => $filteredRelationship,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [];

        // Kiểm tra xem có dữ liệu personal hay company không
        $hasPersonal = $this->customer_type == CustomerTypeEnum::PERSONAL;
        $hasCompany = $this->customer_type == CustomerTypeEnum::COMPANY;

        // Phải có ít nhất một trong hai
        if (!$hasPersonal && !$hasCompany) {
            $rules['personal'] = [
                'required_without:company',
                function ($attribute, $value, $fail) {
                    $fail('Phải có thông tin cá nhân hoặc công ty.');
                }
            ];
            $rules['company'] = [
                'required_without:personal',
                function ($attribute, $value, $fail) {
                    $fail('Phải có thông tin cá nhân hoặc công ty.');
                }
            ];
        }

        // Chỉ validate personal nếu có dữ liệu personal
        if ($hasPersonal) {
            $rules = array_merge($rules, [
                'personal' => ['array'],
                'personal.source_customer' => ['required', 'string'],
                'personal.person_incharge' => ['required', 'integer'],
                'personal.sales_person' => ['required', 'integer'],
                'personal.salutation' => ['required', 'string'],
                'personal.last_name' => ['required', 'string'],
                'personal.first_name' => ['required', 'string'],
                'personal.gender' => ['required', 'string'],
                'personal.phone' => ['required', 'string'],
                'personal.email' => ['required', 'email'],
                'personal.invoice_name' => ['nullable', 'string'],
                'personal.invoice_tax_code' => ['nullable', 'string'],
                'personal.invoice_email' => ['nullable', 'email'],
                'bank.personal.account_number' => ['nullable', 'string'],
                'bank.personal.account_name' => ['nullable', 'string'],
                'bank.personal.name' => ['nullable', 'string'],
                'bank.personal.branch' => ['nullable', 'string'],
                'files.*' => ['nullable', 'max:10240'],
            ]);
        }

        // Chỉ validate company nếu có dữ liệu company
        if ($hasCompany) {
            $rules = array_merge($rules, [
                'company' => ['array'],
                'company.source_customer' => ['required', 'string'],
                'company.person_incharge' => ['required', 'integer'],
                'company.sales_person' => ['required', 'integer'],
                'company.company_name' => ['required', 'string'],
                'company.tax_code' => ['required', 'string'],
                'company.email' => ['required', 'email'],
                'company.sub_email' => ['nullable', 'email'],
                'company.invoice_name' => ['nullable', 'string'],
                'company.invoice_tax_code' => ['nullable', 'string'],
                'company.invoice_email' => ['nullable', 'email'],
                'bank.company.account_number' => ['nullable', 'string'],
                'bank.company.account_name' => ['nullable', 'string'],
                'bank.company.name' => ['nullable', 'string'],
                'bank.company.branch' => ['nullable', 'string'],
                'files.*' => ['nullable', 'max:10240'],
            ]);
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'personal.required_without' => 'Phải nhập thông tin ít nhất một trong hai: Cá nhân hoặc Công ty.',
            'company.required_without' => 'Phải nhập thông tin ít nhất một trong hai: Cá nhân hoặc Công ty.',
            'personal.source_customer.required' => 'Nguồn khách hàng không được để trống.',
            'personal.person_incharge.required' => 'Nhân viên chăm sóc KH không được để trống.',
            'personal.sales_person.required' => 'Nhân viên Sale không được để trống.',
            'personal.salutation.required' => 'Xưng hô không được để trống.',
            'personal.last_name.required' => 'Họ và tên đệm không được để trống.',
            'personal.first_name.required' => 'Tên không được để trống.',
            'personal.gender.required' => 'Giới tính không được để trống.',
            'personal.phone.required' => 'Số điện thoại không được để trống.',
            'personal.email.required' => 'Email không được để trống.',
            // CÔNG TY
            'company.source_customer.required' => 'Nguồn khách hàng không được để trống.',
            'company.person_incharge.required' => 'Nhân viên chăm sóc KH không được để trống.',
            'company.sales_person.required' => 'Nhân viên Sale không được để trống.',
            'company.company_name.required' => 'Tên công ty không được để trống.',
            'company.tax_code.required' => 'Mã số thuế không được để trống.',
            'company.email.required' => 'Email không được để trống.',
            'company.sub_email.required' => 'Email khác không được để trống.',
            'files.*.max' => 'File không được vượt quá 10MB.',
        ];
    }
}
