<?php

declare(strict_types= 1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="RoleRequest",
 *     type="object",
 *     required={"name", "scope"},
 *     @OA\Property(property="name", type="string", maxLength=50, example="warehouse_manager"),
 *     @OA\Property(property="display_name", type="string", maxLength=100, nullable=true, example="Warehouse Manager"),
 *     @OA\Property(property="scope", type="string", enum={"global", "shop"}, example="shop")
 * )
 */
class RoleRequest extends BaseFormRequest
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
            'name'         => ['required','string','max:50','unique:roles,name',Rule::unique('roles','name')->ignore($this->route('role'))],
            'display_name' => 'nullable|string|max:100',
            'scope'        => 'required|in:global,shop',
        ];
    }

    public function messages(): array
    {
        return [
            // Tên quyền
            'name.required' => 'Vui lòng nhập tên quyền.',
            'name.string'   => 'Tên quyền phải là dạng văn bản.',
            'name.max'      => 'Tên quyền không được vượt quá 50 ký tự.',
            'name.unique'   => 'Tên quyền này đã được sử dụng. Vui lòng chọn tên khác.',

            // Tên hiển thị
            'display_name.string' => 'Tên hiển thị phải là dạng văn bản.',
            'display_name.max'    => 'Tên hiển thị không được vượt quá 100 ký tự.',

            // Phạm vi
            'scope.required' => 'Vui lòng chọn phạm vi áp dụng.',
            'scope.in'      => 'Phạm vi không hợp lệ. Chỉ chấp nhận: global (toàn hệ thống) hoặc shop (cửa hàng).',
        ];
    }
}
