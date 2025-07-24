<?php

declare(strict_types= 1);
namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="PermissionRoleUpdateRequest",
 *     type="object",
 *     @OA\Property(property="role_id", type="integer", example=3),
 *     @OA\Property(property="permission_id", type="integer", example=12)
 * )
 */
class PermissionRoleUpdateRequest extends BaseFormRequest
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
            'role_id'       => 'sometimes|exists:roles,id',
            'permission_id' => 'sometimes|exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.exists'         => 'Vai trò không tồn tại.',
            'permission_id.exists'   => 'Quyền hạn không tồn tại.',
        ];
    }
}
