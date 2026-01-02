<?php

namespace Modules\Employee\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordPersonalRequest extends FormRequest
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
            'old_password' => 'required|string|min:8|current_password',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'old_password.required' => 'Mật khẩu cũ không được để trống',
            'old_password.current_password' => 'Mật khẩu cũ không đúng',
            'new_password.required' => 'Mật khẩu mới không được để trống',
            'new_password.confirmed' => 'Mật khẩu mới không khớp',
            'new_password_confirmation.required' => 'Mật khẩu nhập lại không được để trống',
            'new_password_confirmation.min' => 'Mật khẩu nhập lại phải có ít nhất 8 ký tự',
            'new_password_confirmation.confirmed' => 'Mật khẩu nhập lại không khớp',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'new_password.confirmed' => 'Mật khẩu mới không khớp',
        ];
    }
}
