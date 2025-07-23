<?php

declare(strict_types= 1);
namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class RoleUpdateRequest extends BaseFormRequest
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
            'name'         => ['sometimes','string','max:50',Rule::unique('roles', 'name')->ignore($this->route('role')),],
            'display_name' => 'sometimes|nullable|string|max:100',
            'scope'        => 'sometimes|in:global,shop',
        ];
    }

    public function messages(): array
    {
        return [
            // Tên quyền
            'name.string'   => 'Tên quyền phải là dạng văn bản.',
            'name.max'      => 'Tên quyền không được vượt quá 50 ký tự.',
            'name.unique'   => 'Tên quyền này đã được sử dụng. Vui lòng chọn tên khác.',

            // Tên hiển thị
            'display_name.string' => 'Tên hiển thị phải là dạng văn bản.',
            'display_name.max'    => 'Tên hiển thị không được vượt quá 100 ký tự.',

            // Phạm vi
            'scope.in'      => 'Phạm vi không hợp lệ. Chỉ chấp nhận: global (toàn hệ thống) hoặc shop (cửa hàng).',
        ];
    }
}
