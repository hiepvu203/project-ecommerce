<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

/**
 * App\Models\Product
 * @property string $status
 * @property string|null $rejection_reason
 */

/**
 * @OA\Schema(
 *     schema="VerifyRequest",
 *     type="object",
 *     required={"rejection_reason"},
 *     @OA\Property(
 *         property="rejection_reason",
 *         type="string",
 *         maxLength=255,
 *         description="Lý do từ chối phê duyệt sản phẩm"
 *     )
 * )
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
            'rejection_reason' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'rejection_reason.required' => 'Lý do từ chối là bắt buộc.',
            'rejection_reason.string' => 'Lý do từ chối phải là chuỗi ký tự.',
            'rejection_reason.max' => 'Lý do từ chối không được vượt quá 255 ký tự.',
        ];
    }
}
