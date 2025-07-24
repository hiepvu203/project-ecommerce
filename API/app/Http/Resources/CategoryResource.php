<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="CategoryResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="name", type="string", example="Điện thoại"),
 *     @OA\Property(property="slug", type="string", example="dien-thoai"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="order_position", type="integer", example=2),
 *     @OA\Property(property="is_featured", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date", example="24-07-2025"),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CategoryResource")
 *     )
 * )
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'parent_id'      => $this->parent_id,
            'order_position' => $this->order_position,
            'is_featured'    => $this->is_featured,
            'created_at'     => $this->created_at?->format('d-m-Y'),
            // 'updated_at'     => $this->updated_at,
            'children'       => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
