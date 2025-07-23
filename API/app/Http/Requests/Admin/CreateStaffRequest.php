<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

class CreateStaffRequest extends BaseFormRequest
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
        return[
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            // 'role'     => 'required|exists:roles,name',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên người dùng là bắt buộc!',
            'name.string'   => 'Tên phải là chuỗi ký tự!',
            'name.max'      => 'Tên không được vượt quá 255 ký tự!',
            'email.required' => 'Email là bắt buộc!',
            'email.email'    => 'Email không đúng định dạng!',
            'email.max'      => 'Email không được vượt quá 255 ký tự!',
            'email.unique'   => 'Email này đã được sử dụng!',
            'password.required'  => 'Mật khẩu là bắt buộc!',
            'password.string'    => 'Mật khẩu phải là chuỗi ký tự!',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự!',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp!',
            // 'role.required' => 'Vai trò là bắt buộc!',
            // 'role.exists'   => 'Vai trò không hợp lệ!',
            'roles.required' => 'Vai trò là bắt buộc!',
            'roles.array'    => 'Vai trò phải là một mảng!',
            'roles.min'      => 'Phải chọn ít nhất một vai trò!',
            'roles.*.exists' => 'Vai trò không hợp lệ!',
        ];
    }
}
