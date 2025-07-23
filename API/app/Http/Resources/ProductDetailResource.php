<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
