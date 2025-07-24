<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="DiscountCodeResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=15),
 *     @OA\Property(property="shop_id", type="integer", example=7),
 *     @OA\Property(property="code", type="string", example="SUMMER2025"),
 *     @OA\Property(property="type", type="string", enum={"amount","percent","freeship"}, example="percent"),
 *     @OA\Property(property="value", type="number", example=10),
 *     @OA\Property(property="min_order_amount", type="number", nullable=true, example=100000),
 *     @OA\Property(property="usage_limit", type="integer", nullable=true, example=100),
 *     @OA\Property(property="usage_per_user", type="integer", nullable=true, example=1),
 *     @OA\Property(property="start_at", type="string", format="date", example="24-07-2025"),
 *     @OA\Property(property="end_at", type="string", format="date", example="31-08-2025"),
 *     @OA\Property(property="active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date", example="24-07-2025")
 * )
 */
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
