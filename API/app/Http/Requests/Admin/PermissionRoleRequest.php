<?php

declare(strict_types= 1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

class PermissionRoleRequest extends BaseFormRequest
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
            'role_id'       => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.required'       => 'Vai trò là bắt buộc.',
            'role_id.exists'         => 'Vai trò không tồn tại.',
            'permission_id.required' => 'Quyền hạn là bắt buộc.',
            'permission_id.exists'   => 'Quyền hạn không tồn tại.',
        ];
    }
}
