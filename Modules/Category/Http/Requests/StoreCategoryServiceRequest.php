<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Category\Enums\PaymentTypeEnum;
use Modules\Category\Enums\PaymentPeriodEnum;

class StoreCategoryServiceRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:255',
            'payment_type' => 'required|integer|in:' . implode(',', PaymentTypeEnum::getValues()),
            'vat' => 'required|integer',
            'status' => 'required|integer',
            'products' => 'required|array|min:1',
            'products.*.payment_period' => 'required|integer|in:' . implode(',', PaymentPeriodEnum::getValues()),
            'products.*.package_period' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'description' => 'string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'name.required' => 'Vui lòng nhập tên dịch vụ',
            'name.min' => 'Tên dịch vụ phải có ít nhất 2 ký tự',
            'name.max' => 'Tên dịch vụ không được quá 255 ký tự',
            'payment_type.required' => 'Vui lòng chọn loại thanh toán',
            'payment_type.in' => 'Loại thanh toán không hợp lệ',
            'vat.required' => 'Vui lòng chọn VAT',
            'status.required' => 'Vui lòng chọn trạng thái',
            'products.required' => 'Vui lòng nhập ít nhất một gói dịch vụ',
            'products.min' => 'Vui lòng nhập ít nhất một gói dịch vụ',
            'products.*.payment_period.required' => 'Vui lòng chọn kỳ thanh toán',
            'products.*.payment_period.in' => 'Kỳ thanh toán không hợp lệ',
            'products.*.package_period.required' => 'Vui lòng nhập thời hạn',
            //'products.*.package_period.min' => 'Thời hạn phải lớn hơn 0',
            'products.*.price.required' => 'Vui lòng nhập đơn giá',
            'products.*.price.numeric' => 'Đơn giá phải là số',
            'products.*.price.min' => 'Đơn giá phải lớn hơn 0',
        ];
    }
}
