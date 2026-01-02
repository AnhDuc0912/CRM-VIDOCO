<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_service' => 'nullable|integer|exists:category_services,id',
        ];
    }
}
