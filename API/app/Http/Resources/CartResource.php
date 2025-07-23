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
