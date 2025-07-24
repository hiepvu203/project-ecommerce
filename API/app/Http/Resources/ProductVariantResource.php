<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductVariantResource",
 *     type="object",
 *     title="Product Variant Resource",
 *     @OA\Property(property="id", type="integer", example=11, description="ID biến thể"),
 *     @OA\Property(property="product_id", type="integer", example=101, description="ID sản phẩm cha"),
 *     @OA\Property(property="name", type="string", example="Màu sắc", description="Tên thuộc tính biến thể"),
 *     @OA\Property(property="value", type="string", example="Đỏ", description="Giá trị thuộc tính biến thể"),
 *     @OA\Property(property="price_adjustment", type="number", format="float", example=20000, description="Giá điều chỉnh cho biến thể"),
 *     @OA\Property(property="quantity", type="integer", example=5, description="Số lượng tồn kho của biến thể"),
 *     @OA\Property(property="sku", type="string", example="SKU-RED-XL", description="Mã SKU của biến thể"),
 *     @OA\Property(property="created_at", type="string", format="date", example="2024-07-12", description="Ngày tạo")
 * )
 */
class ProductVariantResource extends JsonResource
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
            'product_id' => $this->product_id,
            'name' => $this->name,
            'value' => $this->value,
            'price_adjustment' => $this->price,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'created_at' => $this->created_at?->format('d-m-Y'),
            // 'updated_at' => $this->updated_at,
        ];
    }
}
