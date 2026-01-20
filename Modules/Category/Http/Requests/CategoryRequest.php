<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'service_field_id' => 'nullable|integer|exists:service_fields,id',
            'status' => 'required|string|max:255',
            'files.*' => 'nullable|file|mimes:xlsx,xls,jpg,jpeg,png,gif,doc,docx,mov,ppt,pptx,txt,pdf|max:10240',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
