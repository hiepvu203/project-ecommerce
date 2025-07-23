<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Http\Requests\BaseFormRequest;

class CreateProductRequest extends BaseFormRequest
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
            'category_id'                 => 'nullable|exists:system_categories,id',
            'name'                        => 'required|string|max:255',
            'slug'                        => 'string|max:255|unique:products,slug',
            'description'                 => 'required|string',
            'price'                       => 'required|numeric|min:0',
            'compare_price'               => 'nullable|numeric|min:0',
            'quantity'                    => 'required|integer|min:0',
            'sku'                         => 'nullable|string|max:100',
            'is_featured'                 => 'boolean',

            'image'                       => 'required|array|min:1',
            'image.*'                     => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'variants'                    => 'nullable|array',
            'variants.*.name'             => 'required|string|max:100',
            'variants.*.value'            => 'required|string|max:100',
            'variants.*.price_adjustment' => 'numeric|min:0',
            'variants.*.quantity'         => 'integer|min:0',
            'variants.*.sku'              => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            // Thông tin cơ bản
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',

            'slug.required' => 'Đường dẫn tĩnh là bắt buộc.',
            'slug.string' => 'Đường dẫn tĩnh phải là chuỗi ký tự.',
            'slug.max' => 'Đường dẫn tĩnh không được vượt quá 255 ký tự.',
            'slug.unique' => 'Đường dẫn tĩnh này đã được sử dụng.',

            'description.string' => 'Mô tả sản phẩm phải là chuỗi ký tự.',

            // Giá và tồn kho
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',

            'compare_price.numeric' => 'Giá so sánh phải là số.',
            'compare_price.min' => 'Giá so sánh không được nhỏ hơn 0.',

            'quantity.required' => 'Số lượng tồn kho là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng không được nhỏ hơn 0.',

            'sku.string' => 'SKU phải là chuỗi ký tự.',
            'sku.max' => 'SKU không được vượt quá 100 ký tự.',

            'is_featured.boolean' => 'Trạng thái nổi bật phải là true hoặc false.',

            // Hình ảnh
            'image.array' => 'Danh sách hình ảnh không hợp lệ.',
            'image.min' => 'Cần ít nhất 1 hình ảnh.',

            'image.*.image' => 'File tải lên phải là hình ảnh.',
            'image.*.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png hoặc webp.',
            'image.*.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',

            // Biến thể sản phẩm
            'variants.array' => 'Danh sách biến thể không hợp lệ.',

            'variants.*.name.required' => 'Tên biến thể là bắt buộc.',
            'variants.*.name.string' => 'Tên biến thể phải là chuỗi ký tự.',
            'variants.*.name.max' => 'Tên biến thể không được vượt quá 100 ký tự.',

            'variants.*.value.required' => 'Giá trị biến thể là bắt buộc.',
            'variants.*.value.string' => 'Giá trị biến thể phải là chuỗi ký tự.',
            'variants.*.value.max' => 'Giá trị biến thể không được vượt quá 100 ký tự.',

            'variants.*.price_adjustment.numeric' => 'Điều chỉnh giá phải là số.',
            'variants.*.price_adjustment.min' => 'Điều chỉnh giá không được nhỏ hơn 0.',

            'variants.*.quantity.required' => 'Số lượng biến thể là bắt buộc.',
            'variants.*.quantity.integer' => 'Số lượng biến thể phải là số nguyên.',
            'variants.*.quantity.min' => 'Số lượng biến thể không được nhỏ hơn 0.',

            'variants.*.sku.string' => 'SKU biến thể phải là chuỗi ký tự.',
            'variants.*.sku.max' => 'SKU biến thể không được vượt quá 100 ký tự.',
        ];
    }
}
