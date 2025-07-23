<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * App\Models\DiscountCode
 *
 * @property int $id
 * @property Shop $shop
 * @property string $name
 * @property int $quantity
 * @property bool $is_featured
 * @property float $price
 * @property float $compare_price
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            // 'slug'          => $this->slug,
            // 'description'   => $this->description,
            'price'         => $this->price,
            'compare_price' => $this->compare_price,
            'quantity'      => $this->quantity,
            'is_featured'   => $this->is_featured,
            'category_id'   => $this->category_id,
            'shop'          => [
                'id'    => $this->shop->id,
                'name'  => $this->shop->name,
                // 'slug'  => $this->shop->slug,
            ],
            'images' => $this->images->map(fn($img) => [
                'url' => $img->image,
                'order' => $img->order_position,
            ]),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
