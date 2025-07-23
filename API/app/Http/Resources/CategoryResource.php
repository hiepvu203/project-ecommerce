<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
