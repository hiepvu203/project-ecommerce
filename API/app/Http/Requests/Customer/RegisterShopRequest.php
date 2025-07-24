<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="RegisterShopRequest",
 *     type="object",
 *     required={"name","slug","phone","address","city","country"},
 *     @OA\Property(property="name", type="string", maxLength=255, example="Cool Gadgets"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="cool-gadgets"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Best gadgets in town"),
 *     @OA\Property(property="phone", type="string", maxLength=10, example="0909123123"),
 *     @OA\Property(property="address", type="string", example="123 Lê Lợi, Q1, TP.HCM"),
 *     @OA\Property(property="city", type="string", maxLength=100, example="Hồ Chí Minh"),
 *     @OA\Property(property="country", type="string", maxLength=100, example="Việt Nam"),
 *     @OA\Property(property="payment_methods", type="array", @OA\Items(type="string"), example={"cod","bank_transfer"}),
 *     @OA\Property(property="shipping_config", type="object", example={"standard":15000,"express":30000}),
 *     @OA\Property(property="commission_rate", type="number", minimum=0, maximum=100, example=5),
 *     @OA\Property(property="logo_url", type="string", format="binary", description="Shop logo image"),
 *     @OA\Property(property="cover_image_url", type="string", format="binary", description="Shop cover image")
 * )
 */
class RegisterShopRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->user() && $this->user()->type === 'customer';
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
            'name'                   => 'required|string|max:255',
            'slug'                   => 'string|max:255|unique:shops,slug',
            'description'            => 'nullable|string',
            'logo_url'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image_url'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'phone'                  => 'required|string|max:10',
            'address'                => 'required|string',
            'city'                   => 'required|string|max:100',
            'country'                => 'required|string|max:100',
            'payment_methods'        => 'nullable|array',
            'shipping_config'        => 'nullable|array',
            'commission_rate'        => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            // Tên cửa hàng
            'name.required' => 'Tên cửa hàng là bắt buộc.',
            'name.string' => 'Tên cửa hàng phải là chuỗi ký tự.',
            'name.max' => 'Tên cửa hàng không được vượt quá 255 ký tự.',

            // Đường dẫn
            'slug.required' => 'Đường dẫn tĩnh là bắt buộc.',
            'slug.string' => 'Đường dẫn tĩnh phải là chuỗi ký tự.',
            'slug.max' => 'Đường dẫn tĩnh không được vượt quá 255 ký tự.',
            'slug.unique' => 'Đường dẫn tĩnh này đã được sử dụng.',

            // Mô tả
            'description.string' => 'Mô tả cửa hàng phải là chuỗi ký tự.',

            // Logo
            'logo_url.image' => 'Logo phải là file hình ảnh.',
            'logo_url.mimes' => 'Logo phải có định dạng jpg, jpeg, png hoặc webp.',
            'logo_url.max' => 'Logo không được vượt quá 2MB.',

            // Ảnh bìa
            'cover_image_url.image' => 'Ảnh bìa phải là file hình ảnh.',
            'cover_image_url.mimes' => 'Ảnh bìa phải có định dạng jpg, jpeg, png hoặc webp.',
            'cover_image_url.max' => 'Ảnh bìa không được vượt quá 4MB.',

            // Số điện thoại
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự.',

            // Địa chỉ
            'address.required' => 'Địa chỉ cửa hàng là bắt buộc.',
            'address.string' => 'Địa chỉ cửa hàng phải là chuỗi ký tự.',

            // Thành phố
            'city.required' => 'Thành phố là bắt buộc.',
            'city.string' => 'Thành phố phải là chuỗi ký tự.',
            'city.max' => 'Tên thành phố không được vượt quá 100 ký tự.',

            // Quốc gia
            'country.required' => 'Quốc gia là bắt buộc.',
            'country.string' => 'Quốc gia phải là chuỗi ký tự.',
            'country.max' => 'Tên quốc gia không được vượt quá 100 ký tự.',

            // Phương thức thanh toán
            'payment_methods.array' => 'Phương thức thanh toán không hợp lệ.',

            // Cấu hình vận chuyển
            'shipping_config.array' => 'Cấu hình vận chuyển không hợp lệ.',

            // Tỷ lệ hoa hồng
            'commission_rate.numeric' => 'Tỷ lệ hoa hồng phải là số.',
            'commission_rate.min' => 'Tỷ lệ hoa hồng không được nhỏ hơn 0%.',
            'commission_rate.max' => 'Tỷ lệ hoa hồng không được vượt quá 100%.',
        ];
    }
}
