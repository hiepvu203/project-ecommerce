<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

class CreateSaleRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_at' => 'required|date|after_or_equal:today',
            'end_at' => 'required|date|after:start_at',
            'active' => 'boolean',
            'products' => 'required|array|min:1',
            'products.*' => 'integer|exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            // Name messages
            'name.required' => 'Tên chương trình khuyến mãi là bắt buộc.',
            'name.string'   => 'Tên chương trình phải là chuỗi ký tự.',
            'name.max'      => 'Tên chương trình không được vượt quá 255 ký tự.',

            // Description messages
            'description.string' => 'Mô tả phải là chuỗi ký tự.',

            // Discount percent messages
            'discount_percent.numeric' => 'Phần trăm giảm giá phải là số.',
            'discount_percent.min'     => 'Phần trăm giảm giá không được nhỏ hơn 0%.',
            'discount_percent.max'     => 'Phần trăm giảm giá tối đa là 100%.',

            // Discount amount messages
            'discount_amount.numeric' => 'Số tiền giảm giá phải là số.',
            'discount_amount.min'    => 'Số tiền giảm giá không được nhỏ hơn 0.',

            // Start date messages
            'start_at.required'       => 'Ngày bắt đầu là bắt buộc.',
            'start_at.date'           => 'Ngày bắt đầu không hợp lệ.',
            'start_at.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',

            // End date messages
            'end_at.required' => 'Ngày kết thúc là bắt buộc.',
            'end_at.date'     => 'Ngày kết thúc không hợp lệ.',
            'end_at.after'    => 'Ngày kết thúc phải sau ngày bắt đầu.',

            // Active messages
            'active.boolean' => 'Trạng thái kích hoạt phải là true hoặc false.',

            // Products messages
            'products.required' => 'Phải chọn ít nhất 1 sản phẩm.',
            'products.array'    => 'Danh sách sản phẩm không hợp lệ.',
            'products.min'      => 'Phải chọn ít nhất 1 sản phẩm.',

            // Product items messages
            'products.*.integer' => 'ID sản phẩm phải là số nguyên.',
            'products.*.exists'  => 'Sản phẩm không tồn tại trong hệ thống.',
        ];
    }
}
