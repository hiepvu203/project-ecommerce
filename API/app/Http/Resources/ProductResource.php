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

/**
 * @OA\Schema(
 *     schema="ProductResource",
 *     type="object",
 *     title="Product Resource",
 *     @OA\Property(property="id", type="integer", example=101, description="ID sản phẩm"),
 *     @OA\Property(property="name", type="string", example="Áo thun nam", description="Tên sản phẩm"),
 *     @OA\Property(property="price", type="number", format="float", example=199000, description="Giá bán"),
 *     @OA\Property(property="compare_price", type="number", format="float", example=250000, description="Giá gốc"),
 *     @OA\Property(property="quantity", type="integer", example=10, description="Số lượng còn lại"),
 *     @OA\Property(property="is_featured", type="boolean", example=true, description="Sản phẩm nổi bật"),
 *     @OA\Property(property="category_id", type="integer", example=5, description="ID danh mục"),
 *     @OA\Property(
 *         property="shop",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=2, description="ID shop"),
 *         @OA\Property(property="name", type="string", example="Shop ABC", description="Tên shop")
 *     ),
 *     @OA\Property(
 *         property="images",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="url", type="string", example="https://example.com/image.jpg", description="Đường dẫn ảnh"),
 *             @OA\Property(property="order", type="integer", example=1, description="Thứ tự hiển thị")
 *         ),
 *         description="Danh sách ảnh sản phẩm"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-12 10:00:00", description="Ngày tạo")
 * )
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
