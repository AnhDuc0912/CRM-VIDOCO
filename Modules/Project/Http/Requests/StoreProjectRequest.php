<?php

namespace Modules\Project\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_code'   => 'required|string|max:255|unique:projects,project_code',
            'project_name'   => 'required|string|max:255',
            'group'          => 'nullable|string|max:255',
            'start_date'     => 'required|date|after_or_equal:today',
            'end_date'       => 'required|date|after:start_date',
            'customer_id'    => 'required|exists:customers,id',
            'manager_id'     => 'required|exists:employees,id',
            'assignees'      => 'nullable|json',
            'follow_id'      => 'nullable|json',
            'description'    => 'nullable|string',
            'attachments'    => 'nullable|json',
            'budget_min'     => 'nullable|numeric|min:0',
            'budget_max'     => 'nullable|numeric|min:0|gte:budget_min',
            'zalo_group'     => 'nullable|string|max:255',
            'auto_email'     => 'integer',
            'progress_calculate' => 'nullable|numeric|min:0|max:100',
            'level'          => 'nullable|integer|min:1',
            'status'         => 'required|integer|in:0,1,2',
        ];
    }

    public function messages(): array
    {
        return [
            'project_code.required' => 'Vui lòng nhập mã dự án',
            'project_code.unique'   => 'Mã dự án đã tồn tại',
            'project_name.required' => 'Vui lòng nhập tên dự án',

            'start_date.required'   => 'Vui lòng chọn ngày bắt đầu',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi',

            'end_date.required'     => 'Vui lòng chọn ngày kết thúc',
            'end_date.after'        => 'Ngày kết thúc phải sau ngày bắt đầu',

            'customer_id.required'  => 'Vui lòng chọn khách hàng',
            'customer_id.exists'    => 'Khách hàng không tồn tại',

            'manager_id.required'   => 'Vui lòng chọn quản lý dự án',
            'manager_id.exists'     => 'Quản lý không tồn tại',

            'budget_max.gte'        => 'Ngân sách tối đa phải lớn hơn hoặc bằng ngân sách tối thiểu',

            'status.required'       => 'Vui lòng chọn trạng thái',
        ];
    }
}
