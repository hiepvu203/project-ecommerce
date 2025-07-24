<?php

declare(strict_types= 1);

namespace App\Http\Resources\Short;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserShortResource",
 *     type="object",
 *     @OA\Property(property="name", type="string", example="Nguyễn Văn A"),
 *     @OA\Property(property="email", type="string", format="email", example="staff@example.com"),
 *     @OA\Property(property="type", type="string", example="shop_owner")
 * )
 */
class UserShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
        ];
    }
}
