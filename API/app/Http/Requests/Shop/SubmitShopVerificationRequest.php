<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Enums\DocumentTypeEnum;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\TraitUseAdaptation;

/**
 * @OA\Schema(
 *     schema="SubmitShopVerificationRequest",
 *     type="object",
 *     required={"document_type", "document_front_url"},
 *     @OA\Property(
 *         property="document_type",
 *         type="string",
 *         enum={"CCCD", "CMND", "PASSPORT", "BUSINESS_LICENSE"},
 *         example="CCCD",
 *         description="Type of document to verify the shop"
 *     ),
 *     @OA\Property(
 *         property="document_front_url",
 *         type="string",
 *         format="binary",
 *         description="Front-side image of the document (max 2MB)"
 *     ),
 *     @OA\Property(
 *         property="document_back_url",
 *         type="string",
 *         format="binary",
 *         description="Back-side image of the document (max 2MB, optional)"
 *     )
 * )
 */
class SubmitShopVerificationRequest extends BaseFormRequest
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
            'document_type'      => ['required', Rule::in(DocumentTypeEnum::values())],
            'document_front_url' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'document_back_url'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            // Document type messages
            'document_type.required' => 'Loại giấy tờ là bắt buộc.',
            'document_type.in' => 'Loại giấy tờ không hợp lệ. Vui lòng chọn một trong các loại sau: ' . implode(', ', DocumentTypeEnum::values()) . '.',

            // Front document image messages
            'document_front_url.required' => 'Ảnh mặt trước giấy tờ là bắt buộc.',
            'document_front_url.image' => 'File tải lên phải là hình ảnh.',
            'document_front_url.mimes' => 'Ảnh mặt trước phải có định dạng: jpg, jpeg, png hoặc webp.',
            'document_front_url.max' => 'Ảnh mặt trước không được vượt quá 2MB.',

            // Back document image messages
            'document_back_url.image' => 'File tải lên phải là hình ảnh.',
            'document_back_url.mimes' => 'Ảnh mặt sau phải có định dạng: jpg, jpeg, png hoặc webp.',
            'document_back_url.max' => 'Ảnh mặt sau không được vượt quá 2MB.',
        ];
    }
}
