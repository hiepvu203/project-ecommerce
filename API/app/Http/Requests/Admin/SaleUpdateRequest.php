<?php

declare(strict_types= 1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

class SaleUpdateRequest extends BaseFormRequest
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
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'discount_percent' => 'sometimes|nullable|numeric|min:0|max:100',
            'discount_amount' => 'sometimes|nullable|numeric|min:0',
            'start_at' => 'sometimes|date|after_or_equal:today',
            'end_at' => 'sometimes|date|after:start_at',
            'active' => 'boolean',
            'products' => 'sometimes|array|min:1',
            'products.*' => 'integer|exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            // Tên
            'name.string' => 'Tên phải là dạng chuỗi ký tự.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',

            // Mô tả
            'description.string' => 'Mô tả phải là dạng chuỗi ký tự.',

            // Giảm giá theo phần trăm
            'discount_percent.numeric' => 'Phần trăm giảm giá phải là số.',
            'discount_percent.min' => 'Phần trăm giảm giá không được nhỏ hơn 0%.',
            'discount_percent.max' => 'Phần trăm giảm giá không được vượt quá 100%.',

            // Giảm giá theo số tiền
            'discount_amount.numeric' => 'Số tiền giảm giá phải là số.',
            'discount_amount.min' => 'Số tiền giảm giá không được nhỏ hơn 0.',

            // Ngày bắt đầu
            'start_at.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_at.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',

            // Ngày kết thúc
            'end_at.date' => 'Ngày kết thúc không hợp lệ.',
            'end_at.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',

            // Trạng thái
            'active.boolean' => 'Trạng thái phải là true hoặc false.',

            // Sản phẩm
            'products.array' => 'Danh sách sản phẩm phải là mảng.',
            'products.min' => 'Phải chọn ít nhất 1 sản phẩm.',

            // Từng sản phẩm
            'products.*.integer' => 'ID sản phẩm phải là số nguyên.',
            'products.*.exists' => 'Sản phẩm không tồn tại trong hệ thống.',
        ];
    }
}
