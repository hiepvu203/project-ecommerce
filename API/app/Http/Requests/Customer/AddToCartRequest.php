<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="AddToCartRequest",
 *     type="object",
 *     required={"product_id", "variant_id", "quantity"},
 *     @OA\Property(property="product_id", type="integer", example=101, description="ID sản phẩm"),
 *     @OA\Property(property="variant_id", type="integer", example=5, description="ID biến thể sản phẩm"),
 *     @OA\Property(property="quantity", type="integer", example=2, description="Số lượng")
 * )
 */
class AddToCartRequest extends BaseFormRequest
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
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Product ID is required',
            'product_id.exists' => 'Product not found',
            'variant_id.exists' => 'Product variant not found',
            'quantity.required' => 'Quantity is required',
            'quantity.min' => 'Quantity must be at least 1',
            'quantity.max' => 'Quantity cannot exceed 100',
        ];
    }
}
