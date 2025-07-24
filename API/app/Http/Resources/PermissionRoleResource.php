<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PermissionRoleResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=7),
 *     @OA\Property(property="role_id", type="integer", example=3),
 *     @OA\Property(property="permission_id", type="integer", example=12),
 *     @OA\Property(property="created_at", type="string", format="date", example="24-07-2025")
 * )
 */
class PermissionRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'role_id'       => $this->role_id,
            'permission_id' => $this->permission_id,
            'created_at'    => $this->created_at?->format('d-m-Y'),
            // 'updated_at'    => $this->updated_at,

            // Optionally include related models if loaded
            // 'role'       => $this->whenLoaded('role'),
            // 'permission' => $this->whenLoaded('permission'),
        ];
    }
}
