<?php

declare(strict_types= 1);

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;

class BaseOrderRequest extends BaseFormRequest
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
            'cart_item_ids'     => ['required', 'array', 'min:1'],
            'cart_item_ids.*'   => ['integer', 'exists:cart_items,id'],
            'shipping_method'   => ['required', 'string', 'max:50'],
            'shipping_address'  => ['required', 'array'],
            'shipping_address.name'     => ['required', 'string', 'max:255'],
            'shipping_address.phone'    => ['required', 'string', 'max:20'],
            'shipping_address.address'  => ['required', 'string', 'max:500'],
            'discount_code'     => ['nullable', 'string', 'max:50', 'exists:discount_codes,code'],
            'notes'             => ['nullable', 'string', 'max:500'],

            'payment_method'    => ['sometimes', 'string', 'max:50'],
            'billing_address'   => ['nullable', 'array'],
            'billing_address.name'     => ['nullable', 'string'],
            'billing_address.phone'    => ['nullable', 'string'],
            'billing_address.address'  => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'cart_item_ids.required'    => 'Bạn chưa chọn sản phẩm nào.',
            'cart_item_ids.array'       => 'Danh sách sản phẩm không hợp lệ.',
            'cart_item_ids.min'         => 'Phải chọn ít nhất một sản phẩm.',
            'cart_item_ids.*.integer'   => 'ID sản phẩm phải là số.',
            'cart_item_ids.*.exists'    => 'Một số sản phẩm không tồn tại.',
            // phương thức vận chuyển
            'shipping_method.required' => 'Phương thức vận chuyển là bắt buộc.',
            'shipping_method.string' => 'Phương thức vận chuyển phải là chuỗi ký tự.',
            'shipping_method.max' => 'Phương thức vận chuyển không được vượt quá 50 ký tự.',

            // Địa chỉ giao hàng
            'shipping_address.required' => 'Địa chỉ giao hàng là bắt buộc.',
            'shipping_address.array' => 'Địa chỉ giao hàng không hợp lệ.',

            // Tên người nhận
            'shipping_address.name.required' => 'Tên người nhận là bắt buộc.',
            'shipping_address.name.string' => 'Tên người nhận phải là chuỗi ký tự.',
            'shipping_address.name.max' => 'Tên người nhận không được vượt quá 255 ký tự.',

            // Số điện thoại người nhận
            'shipping_address.phone.required' => 'Số điện thoại người nhận là bắt buộc.',
            'shipping_address.phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'shipping_address.phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',

            // Địa chỉ cụ thể
            'shipping_address.address.required' => 'Địa chỉ giao hàng là bắt buộc.',
            'shipping_address.address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'shipping_address.address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',

            // Mã giảm giá
            'discount_code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'discount_code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'discount_code.exists' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.',

            // Ghi chú
            'notes.string' => 'Ghi chú phải là chuỗi ký tự.',
            'notes.max' => 'Ghi chú không được vượt quá 500 ký tự.',

            // Phương thức thanh toán
            'payment_method.string' => 'Phương thức thanh toán phải là chuỗi ký tự.',
            'payment_method.max' => 'Phương thức thanh toán không được vượt quá 50 ký tự.',

            // Địa chỉ thanh toán
            'billing_address.array' => 'Địa chỉ thanh toán không hợp lệ.',
            'billing_address.name.string' => 'Tên người thanh toán phải là chuỗi ký tự.',
            'billing_address.phone.string' => 'Số điện thoại thanh toán phải là chuỗi ký tự.',
            'billing_address.address.string' => 'Địa chỉ thanh toán phải là chuỗi ký tự.',
        ];
    }
}
