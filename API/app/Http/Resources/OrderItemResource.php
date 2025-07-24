<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="OrderItemResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=3),
 *     @OA\Property(property="owner_id", type="integer", example=15),
 *     @OA\Property(property="name", type="string", example="Cool Gadgets"),
 *     @OA\Property(property="slug", type="string", example="cool-gadgets"),
 *     @OA\Property(property="logo", type="string", format="url", example="https://example.com/logo.png"),
 *     @OA\Property(property="cover_image", type="string", format="url", example="https://example.com/cover.png"),
 *     @OA\Property(property="description", type="string", example="Best gadgets in town"),
 *     @OA\Property(property="address", type="string", example="123 Lê Lợi, Q1, TP.HCM"),
 *     @OA\Property(property="phone", type="string", example="0909123123"),
 *     @OA\Property(property="email", type="string", format="email", example="shop@example.com"),
 *     @OA\Property(property="shipping_config", type="object"),
 *     @OA\Property(property="payment_methods", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="created_at", type="string", format="date", example="01-01-2024")
 * )
 */
class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'sub_order_id' => $this->sub_order_id,
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'shop_id' => $this->shop_id,

            'product_name' => $this->product_name,
            'variant_name' => $this->variant_name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at?->format('d-m-Y'),

            // Optional relations
            'product' => new ProductResource($this->whenLoaded('product')),
            'variant' => new ProductVariantResource($this->whenLoaded('variant')),
        ];
    }
}
