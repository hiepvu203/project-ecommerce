<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * App\Models\CartItem
 *
 * @property int $cart_id
 * @property int $product_id
 * @property int $variant_id
 * @property int $quantity
 * @property float $price_at_added
 */

/**
 * @OA\Schema(
 *     schema="CartItemResource",
 *     type="object",
 *     title="Cart Item Resource",
 *     @OA\Property(property="cart_id", type="integer", example=1, description="ID giỏ hàng"),
 *     @OA\Property(property="product_id", type="integer", example=101, description="ID sản phẩm"),
 *     @OA\Property(property="variant_id", type="integer", example=5, description="ID biến thể sản phẩm"),
 *     @OA\Property(property="quantity", type="integer", example=2, description="Số lượng"),
 *     @OA\Property(property="price_at_added", type="number", format="float", example=199000, description="Giá tại thời điểm thêm vào giỏ"),
 *     @OA\Property(property="product", ref="#/components/schemas/ProductResource"),
 *     @OA\Property(property="variant", ref="#/components/schemas/ProductVariantResource")
 * )
 */
class CartItemResource extends JsonResource
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
            'product' => new ProductResource($this->whenLoaded('product')),
            'variant' => new ProductVariantResource($this->whenLoaded('variant')),
            //'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
