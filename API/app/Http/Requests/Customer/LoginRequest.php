<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;

/**
 * @property string $email
 * @property string $password
 */

class LoginRequest extends BaseFormRequest
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
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'name.max'      => 'Tên không được vượt quá 255 ký tự!',
            'email.required' => 'Email là bắt buộc!',
            'email.email'    => 'Email không đúng định dạng!',
            'email.max'      => 'Email không được vượt quá 255 ký tự!',
            'email.unique'   => 'Email này đã được sử dụng!',
            'password.required'  => 'Mật khẩu là bắt buộc!',
            'password.string'    => 'Mật khẩu phải là chuỗi ký tự!',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự!',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp!',
        ];
    }
}
