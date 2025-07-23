<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

/**
 * App\Models\Product
 * @property string $status
 * @property string|null $rejection_reason
 */
class VerifyRequest extends BaseFormRequest
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
            'status' => 'required|string|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái phải là "approved" hoặc "rejected".',
            'rejection_reason.required_if' => 'Lý do từ chối là bắt buộc khi trạng thái là "rejected".',
            'rejection_reason.string' => 'Lý do từ chối phải là chuỗi ký tự.',
        ];
    }
}
