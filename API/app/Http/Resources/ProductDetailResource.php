<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductDetailResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=99),
 *     @OA\Property(property="name", type="string", example="Smart Watch X1"),
 *     @OA\Property(property="slug", type="string", example="smart-watch-x1"),
 *     @OA\Property(property="description", type="string", example="Latest smart watch with health tracking"),
 *     @OA\Property(property="price", type="number", format="float", example=1299000),
 *     @OA\Property(property="compare_price", type="number", format="float", example=1499000),
 *     @OA\Property(property="quantity", type="integer", example=50),
 *     @OA\Property(property="sku", type="string", example="SW-X1-BLK"),
 *     @OA\Property(property="is_featured", type="boolean", example=false),
 *     @OA\Property(property="category", type="object",
 *         @OA\Property(property="id", type="integer", example=5),
 *         @OA\Property(property="name", type="string", example="Wearables")
 *     ),
 *     @OA\Property(property="shop", type="object",
 *         @OA\Property(property="id", type="integer", example=3),
 *         @OA\Property(property="name", type="string", example="Cool Gadgets"),
 *         @OA\Property(property="logo_url", type="string", format="uri", example="https://example.com/logo.png"),
 *         @OA\Property(property="address", type="string", example="123 Lê Lợi, Q1, TP.HCM")
 *     ),
 *     @OA\Property(property="images", type="array",
 *         @OA\Items(type="object",
 *             @OA\Property(property="id", type="integer", example=7),
 *             @OA\Property(property="url", type="string", format="uri", example="https://example.com/img1.jpg"),
 *             @OA\Property(property="order_position", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Property(property="variants", type="array",
 *         @OA\Items(type="object",
 *             @OA\Property(property="id", type="integer", example=20),
 *             @OA\Property(property="name", type="string", example="Color"),
 *             @OA\Property(property="value", type="string", example="Black"),
 *             @OA\Property(property="price_adjustment", type="number", format="float", example=0),
 *             @OA\Property(property="quantity", type="integer", example=25),
 *             @OA\Property(property="sku", type="string", example="SW-X1-BLK-42")
 *         )
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-24T10:00:00Z")
 * )
 */
class ProductDetailResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'compare_price' => $this->compare_price,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'is_featured' => $this->is_featured,

            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,

            'shop' => [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
                'logo_url' => $this->shop->logo_url,
                'address' => $this->shop->address,
            ],

            'images' => $this->images->map(fn($image) => [
                'id' => $image->id,
                'url' => $image->image,
                'order_position' => $image->order_position,
            ]),

            'variants' => $this->variants->map(fn($variant) => [
                'id' => $variant->id,
                'name' => $variant->name,
                'value' => $variant->value,
                'price_adjustment' => $variant->price_adjustment,
                'quantity' => $variant->quantity,
                'sku' => $variant->sku,
            ]),

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
