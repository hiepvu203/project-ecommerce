<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserRoleResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=42),
 *     @OA\Property(property="role_id", type="integer", example=3),
 *     @OA\Property(property="shop_id", type="integer", nullable=true, example=7),
 *     @OA\Property(property="created_at", type="string", format="date", example="24-07-2025")
 * )
 */
class UserRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'user_id' => $this->user_id,
            'role_id' => $this->role_id,
            'shop_id' => $this->shop_id,
            'created_at' => $this->created_at?->format('d-m-Y'),


            // 'updated_at' => $this->updated_at,
        ];
    }
}
