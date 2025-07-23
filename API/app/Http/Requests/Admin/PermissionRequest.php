<?php

declare(strict_types= 1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends BaseFormRequest
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
            'name'         => ['required','string','max:100','unique:permissions,name',Rule::unique('permissions','name')->ignore($this->route('permission'))],
            'display_name' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            // Name messages
            'name.required' => 'Tên quyền là bắt buộc.',
            'name.string'   => 'Tên quyền phải là chuỗi ký tự.',
            'name.max'      => 'Tên quyền không được vượt quá 100 ký tự.',
            'name.unique'   => 'Tên quyền này đã tồn tại trong hệ thống.',

            // Display name messages
            'display_name.string' => 'Tên hiển thị phải là chuỗi ký tự.',
            'display_name.max'    => 'Tên hiển thị không được vượt quá 100 ký tự.',
        ];
    }
}
