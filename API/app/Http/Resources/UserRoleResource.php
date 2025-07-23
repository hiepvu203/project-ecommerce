<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


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
