<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'service_id' => 'required|exists:category_services,id',
            'product_id' => 'required|exists:category_service_products,id',
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|boolean',
            'auto_email' => 'boolean',
            'notes' => 'nullable|string',
            'domain' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Vui lòng chọn khách hàng',
            'customer_id.exists' => 'Khách hàng không tồn tại',
            'services.required' => 'Vui lòng chọn ít nhất một dịch vụ',
            'services.array' => 'Dữ liệu dịch vụ không hợp lệ',
            'services.min' => 'Vui lòng chọn ít nhất một dịch vụ',
            'services.*.service_id.required' => 'Vui lòng chọn dịch vụ',
            'services.*.service_id.exists' => 'Dịch vụ không tồn tại',
            'services.*.product_id.required' => 'Vui lòng chọn gói dịch vụ',
            'services.*.product_id.exists' => 'Gói dịch vụ không tồn tại',
            'services.*.notes.max' => 'Ghi chú không được vượt quá 500 ký tự',
        ];
    }
}