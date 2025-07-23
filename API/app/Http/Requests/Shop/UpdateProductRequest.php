<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Http\Requests\BaseFormRequest;

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
