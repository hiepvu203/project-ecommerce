<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="SystemCategoryRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", maxLength=255, example="Điện thoại"),
 *     @OA\Property(property="slug", type="string", maxLength=255, nullable=true, example="dien-thoai"),
 *     @OA\Property(property="image_url", type="string", format="binary", description="Image file (jpg,jpeg,png,webp ≤ 2MB)"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="order_position", type="integer", minimum=0, example=2),
 *     @OA\Property(property="is_featured", type="boolean", example=true)
 * )
 */
class SystemCategoryRequest extends BaseFormRequest
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
            'name'           => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:system_categories,slug',
            'image_url'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'parent_id'      => 'nullable|exists:system_categories,id',
            'order_position' => 'nullable|integer|min:0',
            'is_featured'    => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            // Tên danh mục
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.string'   => 'Tên danh mục phải là dạng văn bản.',
            'name.max'      => 'Tên danh mục không được vượt quá 255 ký tự.',

            // Đường dẫn (slug)
            'slug.string'  => 'Đường dẫn phải là dạng văn bản.',
            'slug.max'     => 'Đường dẫn không được vượt quá 255 ký tự.',
            'slug.unique'  => 'Đường dẫn này đã được sử dụng.',

            // Hình ảnh
            'image_url.image'  => 'File tải lên phải là hình ảnh.',
            'image_url.mimes'  => 'Hình ảnh phải có định dạng JPG, JPEG, PNG hoặc WebP.',
            'image_url.max'    => 'Kích thước hình ảnh không được vượt quá 2MB.',

            // Danh mục cha
            'parent_id.exists' => 'Danh mục cha không tồn tại trong hệ thống.',

            // Vị trí sắp xếp
            'order_position.integer' => 'Vị trí sắp xếp phải là số nguyên.',
            'order_position.min'     => 'Vị trí sắp xếp không được nhỏ hơn 0.',

            // Nổi bật
            'is_featured.boolean' => 'Trường nổi bật phải có giá trị true hoặc false.',
        ];
    }

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
