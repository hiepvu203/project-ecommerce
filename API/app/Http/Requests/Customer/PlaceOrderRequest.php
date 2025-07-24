<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="PlaceOrderRequest",
 *     type="object",
 *     required={"shipping_address_id","billing_address_id","shipping_method","payment_method"},
 *     allOf={
 *        @OA\Schema(ref="#/components/schemas/CheckoutRequest")
 *     },
 *     @OA\Property(property="payment_method", type="string", example="cod")
 * )
 */
class PlaceOrderRequest extends BaseOrderRequest
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
        $rules = parent::rules();

        $rules['payment_method'] = ['required', 'string', 'max:50'];

        return $rules;
    }
}
