<?php

declare(strict_types= 1);

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;

/**
 * @OA\Schema(
 *     schema="CheckoutRequest",
 *     type="object",
 *     required={"shipping_address_id","billing_address_id","shipping_method","payment_method"},
 *     @OA\Property(property="shipping_address_id", type="integer", example=5),
 *     @OA\Property(property="billing_address_id",  type="integer", example=5),
 *     @OA\Property(property="shipping_method",     type="string", example="standard"),
 *     @OA\Property(property="payment_method",      type="string", example="cod"),
 *     @OA\Property(property="coupon_code",         type="string", nullable=true, example="DISCOUNT10"),
 *     @OA\Property(property="notes",               type="string", nullable=true, example="Please ring the bell")
 * )
 */
class CheckoutRequest extends BaseOrderRequest
{
    // extended from BaseOrderRequest
}
