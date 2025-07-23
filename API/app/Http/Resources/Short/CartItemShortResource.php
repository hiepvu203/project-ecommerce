<?php

declare(strict_types=1);

namespace App\Http\Resources\Short;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * App\Models\DiscountCode
 *
 * @property int $cart_id
 * @property int $product_id
 * @property int $variant_id
 * @property int $quantity
 * @property float $price_at_added
 */
class CartItemShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'quantity' => $this->quantity,
            'price_at_added' => $this->price_at_added,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
