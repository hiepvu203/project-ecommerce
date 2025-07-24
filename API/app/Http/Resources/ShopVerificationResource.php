<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ShopVerificationResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=11),
 *     @OA\Property(property="shop_id", type="integer", example=7),
 *     @OA\Property(property="document_type", type="string", example="business_license"),
 *     @OA\Property(property="document_front_url", type="string", format="url", example="https://cdn.example.com/docs/front.jpg"),
 *     @OA\Property(property="document_back_url", type="string", format="url", nullable=true, example="https://cdn.example.com/docs/back.jpg"),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="rejection_reason", type="string", nullable=true, example="Document blurry"),
 *     @OA\Property(property="verified_by", type="integer", nullable=true, example=4),
 *     @OA\Property(property="verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ShopVerificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'document_type' => $this->document_type,
            'document_front_url' => $this->document_front_url,
            'document_back_url' => $this->document_back_url,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'verified_by' => $this->verified_by,
            'verified_at' => $this->verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
