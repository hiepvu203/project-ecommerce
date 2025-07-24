<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateProductRequest",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Smart Watch X2"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="smart-watch-x2"),
 *     @OA\Property(property="description", type="string", example="Updated description"),
 *     @OA\Property(property="price", type="number", format="float", minimum=0, example=1399000),
 *     @OA\Property(property="compare_price", type="number", format="float", minimum=0, nullable=true, example=1599000),
 *     @OA\Property(property="quantity", type="integer", minimum=0, example=60),
 *     @OA\Property(property="sku", type="string", maxLength=100, nullable=true, example="SW-X2-BLK"),
 *     @OA\Property(property="is_featured", type="boolean", example=true),
 *     @OA\Property(property="image", type="array", minItems=1, @OA\Items(type="string", format="binary")),
 *     @OA\Property(
 *         property="variants",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"name","value","quantity"},
 *             @OA\Property(property="name", type="string", maxLength=100, example="Color"),
 *             @OA\Property(property="value", type="string", maxLength=100, example="Silver"),
 *             @OA\Property(property="price_adjustment", type="number", format="float", minimum=0, example=50000),
 *             @OA\Property(property="quantity", type="integer", minimum=0, example=30),
 *             @OA\Property(property="sku", type="string", maxLength=100, nullable=true, example="SW-X2-SLV")
 *         )
 *     )
 * )
 */
class UpdateProductRequest extends BaseFormRequest
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
            'name'                        => 'sometimes|required|string|max:255',
            'slug'                        => 'nullable|string|max:255|unique:products,slug',
            'description'                 => 'nullable|string',
            'price'                       => 'sometimes|required|numeric|min:0',
            'compare_price'               => 'nullable|numeric|min:0',
            'quantity'                    => 'sometimes|required|integer|min:0',
            'sku'                         => 'nullable|string|max:100',
            'is_featured'                 => 'nullable|boolean',

            'image'                       => 'nullable|array|min:1',
            'image.*'                     => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'variants'                    => 'nullable|array',
            'variants.*.name'             => 'required|string|max:100',
            'variants.*.value'            => 'required|string|max:100',
            'variants.*.price_adjustment' => 'nullable|numeric|min:0',
            'variants.*.quantity'         => 'required|integer|min:0',
            'variants.*.sku'              => 'nullable|string|max:100',
        ];
    }
}
