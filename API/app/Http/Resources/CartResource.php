<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Short\CartItemShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * App\Models\Cart
 *
 * @property int $id
 * @property int $user_id
 */

/**
 * @OA\Schema(
 *     schema="CartResource",
 *     type="object",
 *     title="Cart Resource",
 *     @OA\Property(property="id", type="integer", example=1, description="ID của giỏ hàng"),
 *     @OA\Property(property="user_id", type="integer", example=10, description="ID người dùng"),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CartItemShortResource"),
 *         description="Danh sách sản phẩm trong giỏ"
 *     )
 * )
 */
class CartResource extends JsonResource
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
            'user_id' => $this->user_id,
            'items' => CartItemShortResource::collection($this->whenLoaded('items')),
            // 'created_at' => $this->created_at?->format('d-m-Y'),
            // 'updated_at' => $this->updated_at,
        ];
    }
}
