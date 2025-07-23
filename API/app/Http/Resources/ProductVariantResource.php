<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
