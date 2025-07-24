<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="OrderResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=123),
 *     @OA\Property(property="order_number", type="string", example="ORD-20250724-000123"),
 *     @OA\Property(property="user_id", type="integer", example=42),
 *     @OA\Property(property="subtotal", type="number", format="float", example=499000),
 *     @OA\Property(property="shipping_fee", type="number", format="float", example=15000),
 *     @OA\Property(property="discount", type="number", format="float", example=50000),
 *     @OA\Property(property="total", type="number", format="float", example=464000),
 *     @OA\Property(property="payment_method", type="string", example="cod"),
 *     @OA\Property(property="payment_status", type="string", example="pending"),
 *     @OA\Property(property="payment_reference", type="string", nullable=true, example="TXN-XYZ789"),
 *     @OA\Property(property="shipping_method", type="string", example="standard"),
 *     @OA\Property(property="shipping_address", type="object"),
 *     @OA\Property(property="billing_address", type="object"),
 *     @OA\Property(property="status", type="string", example="processing"),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Gift wrap please"),
 *     @OA\Property(property="created_at", type="string", format="date", example="24-07-2025"),
 *     @OA\Property(property="sub_orders", type="array", @OA\Items(ref="#/components/schemas/SubOrderResource"))
 * )
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'user_id' => $this->user_id,
            'subtotal' => $this->subtotal,
            'shipping_fee' => $this->shipping_fee,
            'discount' => $this->discount,
            'total' => $this->total,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'payment_reference' => $this->payment_reference,
            'shipping_method' => $this->shipping_method,
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->format('d-m-Y'),
            // 'updated_at' => $this->updated_at,
            'sub_orders' => SubOrderResource::collection($this->subOrders),
        ];
    }
}
