<?php

namespace Modules\SellOrder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\SellOrder\Enums\SellOrderStatusEnum;

class StoreSellOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'proposal_id' => 'nullable|exists:proposals,id',
            'expired_at' => 'required|date',
            'status' => 'required|in:' . implode(',', array_keys(SellOrderStatusEnum::getStatusOptions())),
            'note' => 'nullable|string|max:500',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'services' => 'required|array',
            'services.*.category_id' => 'required|exists:categories,id',
            'services.*.service_id' => 'required|exists:category_services,id',
            'services.*.product_id' => 'required|exists:category_service_products,id',
            'services.*.price' => 'required|numeric',
            'services.*.quantity' => 'required|numeric',
            'services.*.total' => 'nullable|numeric',
        ];
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
            'customer_id.required' => 'Khách hàng là bắt buộc',
            'customer_id.exists' => 'Khách hàng không tồn tại',
            'expired_at.required' => 'Ngày hết hạn là bắt buộc',
            'expired_at.date' => 'Ngày hết hạn phải là ngày',
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ',
            'note.max' => 'Ghi chú không được vượt quá 500 ký tự',
            'files.array' => 'File không hợp lệ',
            'files.*.file' => 'File không hợp lệ',
            'files.*.max' => 'File không được vượt quá 10MB',
            'files.*.mimes' => 'File không hợp lệ',
            'services.required' => 'Dịch vụ là bắt buộc',
            'services.array' => 'Dịch vụ không hợp lệ',
            'services.*.category_id.required' => 'Danh mục dịch vụ là bắt buộc',
            'services.*.category_id.exists' => 'Danh mục dịch vụ không tồn tại',
            'services.*.service_id.required' => 'Dịch vụ là bắt buộc',
            'services.*.service_id.exists' => 'Dịch vụ không tồn tại',
            'services.*.product_id.required' => 'Sản phẩm là bắt buộc',
            'services.*.product_id.exists' => 'Sản phẩm không tồn tại',
            'services.*.price.required' => 'Giá dịch vụ là bắt buộc',
            'services.*.price.numeric' => 'Giá dịch vụ không hợp lệ',
            'services.*.quantity.required' => 'Số lượng dịch vụ là bắt buộc',
            'services.*.quantity.numeric' => 'Số lượng dịch vụ không hợp lệ',
        ];
    }
}
