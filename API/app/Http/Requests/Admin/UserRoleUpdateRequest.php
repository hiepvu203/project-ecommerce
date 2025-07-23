<?php

declare(strict_types= 1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

class UserRoleUpdateRequest extends BaseFormRequest
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
            'user_id' => 'sometimes|exists:users,id',
            'role_id' => 'sometimes|exists:roles,id',
            'shop_id' => 'nullable|exists:shops,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'ID người dùng không tồn tại. Vui lòng kiểm tra lại.',
            'role_id.exists' => 'ID vai trò không tồn tại. Vui lòng kiểm tra lại.',
            'shop_id.exists' => 'ID cửa hàng không tồn tại. Vui lòng kiểm tra lại.',
        ];
    }
}
