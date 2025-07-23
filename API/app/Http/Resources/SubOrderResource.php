<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubOrderResource extends JsonResource
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
            'shop_id' => $this->shop_id,
            'order_id' => $this->order_id,
            'subtotal' => $this->subtotal,
            'shipping_fee' => $this->shipping_fee,
            'commission' => $this->commission,
            'total' => $this->total,
            'status' => $this->status,
            'cancellation_reason' => $this->cancellation_reason,
            'shipping_method' => $this->shipping_method,
            'shipping_carrier' => $this->shipping_carrier,
            'tracking_number' => $this->tracking_number,
            'created_at' => $this->created_at?->format('d-m-Y'),
            // 'updated_at' => $this->updated_at,

            'shop' => new ShopResource($this->whenLoaded('shop')),
            'items' => OrderItemResource::collection($this->items),
        ];
    }
}
