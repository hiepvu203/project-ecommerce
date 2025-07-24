<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *   schema="UpdateUserProfileRequest",
 *   type="object",
 *   @OA\Property(
 *     property="avatar",
 *     type="string",
 *     format="binary",
 *     description="Ảnh đại diện (jpg, jpeg, png, webp, max 2MB)"
 *   ),
 *   @OA\Property(
 *     property="phone",
 *     type="string",
 *     pattern="^[0-9]{10}$",
 *     example="0123456789"
 *   ),
 *   @OA\Property(
 *     property="birthdate",
 *     type="string",
 *     format="date",
 *     example="2000-01-01"
 *   ),
 *   @OA\Property(
 *     property="address",
 *     type="string",
 *     maxLength=500,
 *     example="123 Đường ABC, Quận 1, TP.HCM"
 *   ),
 *   @OA\Property(
 *     property="gender",
 *     type="string",
 *     enum={"Nam", "Nữ", "Khác"},
 *     example="Nam"
 *   )
 * )
 */
class UpdateUserProfileRequest extends BaseFormRequest
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
            'avatar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'phone'     => 'nullable|regex:/^[0-9]{10}$/',
            'birthdate' => 'nullable|date|before:today',
            'address'   => 'nullable|string|max:500',
            'gender'    => 'nullable|in:Nam,Nữ,Khác|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            // Avatar messages
            'avatar.image' => 'Ảnh đại diện phải là file hình ảnh hợp lệ.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng jpg, jpeg, png hoặc webp.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',

            // Phone messages
            'phone.regex' => 'Số điện thoại phải gồm 10 chữ số.',

            // Birthdate messages
            'birthdate.date' => 'Ngày sinh không hợp lệ.',
            'birthdate.before' => 'Ngày sinh phải trước ngày hôm nay.',

            // Address messages
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được dài quá 500 ký tự.',

            // Gender messages
            'gender.in' => 'Giới tính không hợp lệ. Chỉ chấp nhận: Nam, Nữ hoặc Khác.',
            'gender.max' => 'Giới tính không được dài quá 10 ký tự.'
        ];
    }
}
