<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Http\Requests\BaseFormRequest;

class UpdateMyShopRequest extends BaseFormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'phone' => ['sometimes', 'string', 'max:10'],
            'address' => ['sometimes', 'string'],
            'city' => ['sometimes', 'string', 'max:100'],
            'country' => ['sometimes', 'string', 'max:100'],
            'payment_methods' => ['sometimes', 'nullable', 'array'],
            'shipping_config' => ['sometimes', 'nullable', 'array'],

            'logo_url' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cover_image_url' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            // Thông tin cơ bản
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả sản phẩm phải là chuỗi ký tự.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'city.max' => 'Tên thành phố không được vượt quá 100 ký tự.',
            'city.string' => 'Tên thành phố phải là chuỗi ký tự.',

            'country.string' => 'Tên quốc gia phải là chuỗi ký tự.',
            'country.max' => 'Tên quốc gia không được vượt quá 100 ký tự',

            'payment_methods.array' => 'Danh sách phương thức thanh toán không hợp lệ.',
            'shipping_config.array' => 'Danh sách cấu hình phí vận chuyển không hợp lệ.',

            'logo_url.image' => 'File tải lên phải là hình ảnh.',
            'logo_url.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png hoặc webp.',
            'logo_url.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',

            'cover_image_url.image' => 'File tải lên phải là hình ảnh.',
            'cover_image_url.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png hoặc webp.',
            'cover_image_url.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ];
    }
}
