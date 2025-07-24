<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PermissionResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=12),
 *     @OA\Property(property="name", type="string", maxLength=100, example="shop.manage.products"),
 *     @OA\Property(property="display_name", type="string", maxLength=100, example="Quản lý sản phẩm"),
 *     @OA\Property(property="created_at", type="string", format="date", example="24-07-2025")
 * )
 */
class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'display_name' => $this->display_name,
            'created_at' => $this->created_at?->format('d-m-Y'),
            // 'updated_at'   => $this->updated_at,
        ];
    }
}
