<?php

namespace Modules\Employee\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Employee\Enums\ContractTypeEnum;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Profile
            'profile.first_name' => 'required|string|min:2|max:50',
            'profile.last_name' => 'required|string|min:2|max:50',
            'profile.birthday' => 'required|date|before:today',
            'profile.gender' => 'required|in:1,2',
            'profile.citizen_id_number' => 'required|string',
            'profile.citizen_id_created_date' => 'nullable|date',
            'profile.citizen_id_created_place' => 'nullable|string|max:100',
            'profile.phone' => 'required|string',
            'profile.email_personal' => 'nullable|email|max:100',
            'profile.email_work' => 'required|email|max:100|unique:employees,email_work,' . $this->route('id'),
            'profile.current_address' => 'nullable|string|max:255',
            'profile.permanent_address' => 'nullable|string|max:255',

            // Bank Account
            'bank_account.bank_account_number' => 'required|string',
            'bank_account.bank_account_name' => 'required|string|min:2|max:100',
            'bank_account.bank_branch' => 'nullable|string|max:100',
            'bank_account.bank_name' => 'required|string|max:100',

            // Job Profile
            'job.department_id' => 'required|exists:departments,id',
            'job.current_position' => 'required|integer',
            'job.level' => 'nullable|integer',
            'job.last_position' => 'nullable|integer',
            'job.start_date' => 'required|date',
            'job.manager_id' => 'nullable|exists:employees,id',
            'job.manager_email' => 'nullable|email',
            'job.manager_phone' => 'nullable|string',

            // Contract
            'contract.contract_type' => 'required|in:' . implode(',', ContractTypeEnum::getValues()),
            'contract.start_date' => 'required|date',
            'contract.end_date' => 'nullable|date',

            // Salary
            'salary.base_salary' => 'nullable|string|max:50',
            'salary.basic_salary' => 'nullable|min:0',
            'salary.insurance_salary' => 'nullable|min:0',

            // Dependents (optional)
            'dependent' => 'nullable|array',
            'dependent.*.relationship' => 'required_with:dependent.*.name|in:1,2,3,4,5',
            'dependent.*.name' => 'required_with:dependent.*.relationship|string|min:2|max:100',
            'dependent.*.birthday' => 'nullable|date|before:today',
            'dependent.*.job' => 'nullable|string|max:100',
            'dependent.*.phone' => 'nullable|string',

            // Allowance
            'allowance' => 'nullable|array',
            'allowance.*.name' => 'required_with:allowance.*.amount|string|min:2|max:100',
            'allowance.*.amount' => 'required_with:allowance.*.name|min:0',
            'allowance.*.note' => 'nullable|string|max:255',

            // Benefit
            'benefit' => 'nullable|array',
            'benefit.*.name' => 'required_with:benefit.*.amount|string|min:2|max:100',
            'benefit.*.amount' => 'required_with:benefit.*.name|min:0',
            'benefit.*.note' => 'nullable|string|max:255',

            // Files
            'files' => 'nullable|array',
            'files.avatar' => 'nullable|max:10240',
            'files.id_card_front' => 'nullable|max:10240',
            'files.id_card_back' => 'nullable|max:10240',
            'files.*.other' => 'nullable|max:10240',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            // Personal Profile Messages
            'profile.first_name.required' => 'Vui lòng nhập họ và tên đệm',
            'profile.first_name.min' => 'Họ phải có ít nhất 2 ký tự',
            'profile.last_name.required' => 'Vui lòng nhập tên',
            'profile.last_name.min' => 'Tên phải có ít nhất 2 ký tự',
            'profile.birthday.required' => 'Vui lòng chọn ngày sinh',
            'profile.birthday.before' => 'Ngày sinh phải trước ngày hiện tại',
            'profile.gender.required' => 'Vui lòng chọn giới tính',
            'profile.citizen_id_number.required' => 'Vui lòng nhập số CCCD/CMND',
            'profile.citizen_id_number.regex' => 'Số CCCD/CMND phải từ 9-12 chữ số',
            'profile.phone.required' => 'Vui lòng nhập số điện thoại',
            'profile.phone.regex' => 'Số điện thoại không hợp lệ (VD: 0987654321)',
            'profile.email_work.required' => 'Vui lòng nhập email công việc',
            'profile.email_work.unique' => 'Email công việc đã được sử dụng',

            // Bank Account Messages
            'bank_account.bank_account_number.required' => 'Vui lòng nhập số tài khoản',
            'bank_account.bank_account_number.regex' => 'Số tài khoản phải từ 6-20 chữ số',
            'bank_account.bank_account_name.required' => 'Vui lòng nhập tên chủ tài khoản',
            'bank_account.bank_name.required' => 'Vui lòng nhập tên ngân hàng',

            // Job Profile Messages
            'department_id.required' => 'Vui lòng chọn phòng ban',
            'department_id.exists' => 'Phòng ban không tồn tại',
            'job.current_position.required' => 'Vui lòng nhập chức danh công việc',
            'job.start_date.required' => 'Vui lòng chọn ngày bắt đầu làm việc',
            'contract.contract_type.required' => 'Vui lòng chọn loại hợp đồng',
            'contract.start_date.required' => 'Vui lòng chọn ngày ký hợp đồng',
            'salary.basic_salary.required' => 'Vui lòng nhập lương cơ bản',
            'salary.basic_salary.numeric' => 'Lương cơ bản phải là số',

            // Dependent Messages
            'dependent.*.name.required_with' => 'Vui lòng nhập tên người phụ thuộc',
            'dependent.*.name.min' => 'Tên phải có ít nhất 2 ký tự',
            'dependent.*.phone.regex' => 'Số điện thoại không hợp lệ',

            // Benefit Messages
            'benefit.*.name.required_with' => 'Vui lòng nhập tên phúc lợi',
            'benefit.*.name.min' => 'Tên phúc lợi phải có ít nhất 2 ký tự',
            'benefit.*.amount.required_with' => 'Vui lòng nhập số tiền phúc lợi',
            'benefit.*.amount.min' => 'Số tiền phúc lợi phải là số',

            // Allowance Messages
            'allowance.*.name.required_with' => 'Vui lòng nhập tên phụ cấp',
            'allowance.*.name.min' => 'Tên phụ cấp phải có ít nhất 2 ký tự',
            'allowance.*.amount.required_with' => 'Vui lòng nhập số tiền phụ cấp',
            'allowance.*.amount.min' => 'Số tiền phụ cấp phải là số',

            // Files Messages
            'files.avatar.max' => 'File không được vượt quá 10MB',
            'files.id_card_front.max' => 'File không được vượt quá 10MB',
            'files.id_card_back.max' => 'File không được vượt quá 10MB',
            'files.*.mimes' => 'File không hợp lệ',
            'files.*.max' => 'File không được vượt quá 10MB',
        ];
    }
}
