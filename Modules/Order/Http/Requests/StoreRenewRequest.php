<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRenewRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'renewal_period' => 'required|integer|min:1|max:10',
            'renewal_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'auto_email' => 'boolean',
            'promotion_code' => 'nullable|string|max:50',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'renewal_period.required' => 'Vui lòng chọn kỳ gia hạn',
            'renewal_period.integer' => 'Kỳ gia hạn phải là số nguyên',
            'renewal_period.min' => 'Kỳ gia hạn tối thiểu là 1 năm',
            'renewal_period.max' => 'Kỳ gia hạn tối đa là 10 năm',
            'renewal_amount.required' => 'Vui lòng nhập số tiền gia hạn',
            'renewal_amount.numeric' => 'Số tiền gia hạn phải là số',
            'renewal_amount.min' => 'Số tiền gia hạn phải lớn hơn 0',
            'notes.max' => 'Ghi chú không được quá 500 ký tự',
            'promotion_code.max' => 'Mã khuyến mãi không được quá 50 ký tự',
        ];
    }
}