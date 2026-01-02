<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RenewOrderRequest extends FormRequest
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
            'code' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'services' => 'required|array',
            'services.*.service_id' => 'required|exists:category_services,id',
            'services.*.product_id' => 'required|exists:category_service_products,id',
            'services.*.domain' => 'nullable|string|max:255',
            'services.*.notes' => 'nullable|string|max:500',
            'services.*.price' => 'required|numeric|min:0',
            'services.*.start_date' => 'nullable|date',
            'services.*.end_date' => 'nullable|date|after:services.*.start_date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Mã đơn hàng không được để trống',
            'customer_id.required' => 'Khách hàng không được để trống',
            'customer_id.exists' => 'Khách hàng không tồn tại',
            'services.required' => 'Vui lòng chọn ít nhất một dịch vụ',
            'services.array' => 'Dữ liệu dịch vụ không hợp lệ',
            'services.*.service_id.required' => 'Vui lòng chọn dịch vụ',
            'services.*.service_id.exists' => 'Dịch vụ không tồn tại',
            'services.*.product_id.required' => 'Vui lòng chọn sản phẩm',
            'services.*.product_id.exists' => 'Sản phẩm không tồn tại',
            'services.*.price.required' => 'Giá dịch vụ không được để trống',
            'services.*.price.numeric' => 'Giá dịch vụ phải là số',
            'services.*.price.min' => 'Giá dịch vụ không được âm',
        ];
    }
}
