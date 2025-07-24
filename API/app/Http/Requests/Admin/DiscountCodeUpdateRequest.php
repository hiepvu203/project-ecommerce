<?php

declare(strict_types= 1);

namespace App\Http\Requests\Admin;
use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="DiscountCodeUpdateRequest",
 *     type="object",
 *     @OA\Property(property="code", type="string", maxLength=50, example="SUMMER2025"),
 *     @OA\Property(property="type", type="string", enum={"amount","percent","freeship"}, example="percent"),
 *     @OA\Property(property="value", type="number", minimum=0, example=15),
 *     @OA\Property(property="min_order_amount", type="number", minimum=0, nullable=true, example=150000),
 *     @OA\Property(property="usage_limit", type="integer", minimum=1, nullable=true, example=200),
 *     @OA\Property(property="usage_per_user", type="integer", minimum=1, nullable=true, example=2),
 *     @OA\Property(property="start_at", type="string", format="date", example="2025-08-01"),
 *     @OA\Property(property="end_at", type="string", format="date", example="2025-09-30"),
 *     @OA\Property(property="active", type="boolean", example=false)
 * )
 */
class DiscountCodeUpdateRequest extends BaseFormRequest
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
            'code' => 'sometimes|string|max:50|unique:discount_codes,code',
            'type' => 'sometimes|in:amount,percent,freeship',
            'value' => 'sometimes|numeric|min:0',
            'min_order_amount' => 'sometimes|nullable|numeric|min:0',
            'usage_limit' => 'sometimes|nullable|integer|min:1',
            'usage_per_user' => 'sometimes|nullable|integer|min:1',
            'start_at' => 'sometimes|date|after_or_equal:today',
            'end_at' => 'sometimes|date|after:start_at',
            'active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            // Code messages
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'code.unique' => 'Mã giảm giá này đã tồn tại trong hệ thống.',

            // Type messages
            'type.in' => 'Loại mã giảm giá không hợp lệ. Chọn một trong: amount (giảm tiền), percent (giảm phần trăm), freeship (miễn phí vận chuyển).',

            // Value messages
            'value.numeric' => 'Giá trị giảm giá phải là số.',
            'value.min' => 'Giá trị giảm giá không được nhỏ hơn 0.',

            // Min order amount messages
            'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Giá trị đơn hàng tối thiểu không được âm.',

            // Usage limit messages
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng tối thiểu là 1.',

            // Usage per user messages
            'usage_per_user.integer' => 'Giới hạn sử dụng mỗi người dùng phải là số nguyên.',
            'usage_per_user.min' => 'Giới hạn sử dụng mỗi người dùng tối thiểu là 1.',

            // Date messages
            'start_at.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_at.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'end_at.date' => 'Ngày kết thúc không hợp lệ.',
            'end_at.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',

            // Active messages
            'active.boolean' => 'Trạng thái kích hoạt phải là true hoặc false.',
        ];
    }
}
