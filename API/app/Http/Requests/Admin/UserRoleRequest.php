<?php

declare(strict_types= 1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="UserRoleRequest",
 *     type="object",
 *     required={"user_id", "role_id"},
 *     @OA\Property(property="user_id", type="integer", example=42),
 *     @OA\Property(property="role_id", type="integer", example=3),
 *     @OA\Property(property="shop_id", type="integer", nullable=true, example=7)
 * )
 */
class UserRoleRequest extends BaseFormRequest
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
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'shop_id' => 'nullable|exists:shops,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Trường ID người dùng là bắt buộc.',
            'user_id.exists' => 'ID người dùng không tồn tại trong cơ sở dữ liệu.',

            'role_id.required' => 'Trường ID vai trò là bắt buộc.',
            'role_id.exists' => 'ID vai trò không tồn tại trong cơ sở dữ liệu.',

            'shop_id.exists' => 'ID cửa hàng không tồn tại trong cơ sở dữ liệu.',
        ];
    }
}
