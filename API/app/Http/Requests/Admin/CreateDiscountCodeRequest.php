<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="CreateDiscountCodeRequest",
 *     type="object",
 *     required={"code","type","value","start_at","end_at"},
 *     @OA\Property(property="code", type="string", maxLength=50, example="SUMMER2025"),
 *     @OA\Property(property="type", type="string", enum={"amount","percent","freeship"}, example="percent"),
 *     @OA\Property(property="value", type="number", minimum=0, example=10),
 *     @OA\Property(property="min_order_amount", type="number", minimum=0, nullable=true, example=100000),
 *     @OA\Property(property="usage_limit", type="integer", minimum=1, nullable=true, example=100),
 *     @OA\Property(property="usage_per_user", type="integer", minimum=1, nullable=true, example=1),
 *     @OA\Property(property="start_at", type="string", format="date", example="2025-07-24"),
 *     @OA\Property(property="end_at", type="string", format="date", example="2025-08-31"),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 */
class CreateDiscountCodeRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:discount_codes,code',
            'type' => 'required|in:amount,percent,freeship',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'start_at' => 'required|date|after_or_equal:today',
            'end_at' => 'required|date|after:start_at',
            'active' => 'boolean',
        ];
    }

    public function messages(): array
{
    return [
        // Code messages
        'code.required' => 'Mã giảm giá là bắt buộc.',
        'code.string'    => 'Mã giảm giá phải là chuỗi ký tự.',
        'code.max'       => 'Mã giảm giá không được vượt quá 50 ký tự.',
        'code.unique'    => 'Mã giảm giá đã tồn tại.',

        // Type messages
        'type.required' => 'Loại mã giảm giá là bắt buộc.',
        'type.in'       => 'Loại mã giảm giá không hợp lệ (chỉ chấp nhận: amount, percent, freeship).',

        // Value messages
        'value.required' => 'Giá trị giảm giá là bắt buộc.',
        'value.numeric'  => 'Giá trị giảm giá phải là số.',
        'value.min'      => 'Giá trị giảm giá không được nhỏ hơn 0.',

        // Min order amount messages
        'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
        'min_order_amount.min'     => 'Giá trị đơn hàng tối thiểu không được nhỏ hơn 0.',

        // Usage limit messages
        'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
        'usage_limit.min'     => 'Giới hạn sử dụng phải lớn hơn hoặc bằng 1.',

        // Usage per user messages
        'usage_per_user.integer' => 'Giới hạn sử dụng mỗi người dùng phải là số nguyên.',
        'usage_per_user.min'     => 'Giới hạn sử dụng mỗi người dùng phải lớn hơn hoặc bằng 1.',

        // Start date messages
        'start_at.required'        => 'Ngày bắt đầu là bắt buộc.',
        'start_at.date'            => 'Ngày bắt đầu không hợp lệ.',
        'start_at.after_or_equal'  => 'Ngày bắt đầu phải từ hôm nay trở đi.',

        // End date messages
        'end_at.required' => 'Ngày kết thúc là bắt buộc.',
        'end_at.date'     => 'Ngày kết thúc không hợp lệ.',
        'end_at.after'    => 'Ngày kết thúc phải sau ngày bắt đầu.',

        // Active messages
        'active.boolean' => 'Trạng thái kích hoạt phải là true hoặc false.',
    ];
}
}
