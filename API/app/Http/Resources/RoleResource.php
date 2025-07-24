<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RoleResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=3),
 *     @OA\Property(property="name", type="string", maxLength=50, example="warehouse_manager"),
 *     @OA\Property(property="display_name", type="string", maxLength=100, example="Warehouse Manager"),
 *     @OA\Property(property="scope", type="string", enum={"global", "shop"}, example="shop"),
 *     @OA\Property(property="created_at", type="string", format="date", example="24-07-2025")
 * )
 */
class RoleResource extends JsonResource
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
            'scope'        => $this->scope,
            'created_at'   => $this->created_at?->format('d-m-Y'),
            // 'updated_at'   => $this->updated_at,
        ];
    }
}
