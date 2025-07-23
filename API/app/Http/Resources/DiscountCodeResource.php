<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'shop_id'          => $this->shop_id,
            'code'             => $this->code,
            'type'             => $this->type,
            'value'            => $this->value,
            'min_order_amount' => $this->min_order_amount,
            'usage_limit'      => $this->usage_limit,
            'usage_per_user'   => $this->usage_per_user,
            'start_at'         => $this->start_at?->format('d-m-Y'),
            'end_at'           => $this->end_at?->format('d-m-Y'),
            'active'           => $this->active,
            'created_at'       => $this->created_at?->format('d-m-Y'),
            // 'updated_at'       => $this->updated_at,

            // Optionally include related shop if loaded
            // 'shop' => $this->whenLoaded('shop'),
        ];
    }
}
