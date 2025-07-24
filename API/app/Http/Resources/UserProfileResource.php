<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Short\UserShortResource;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="profile",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=42),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="phone", type="string", example="+84123456789"),
 *     @OA\Property(property="avatar_url", type="string", example="https://example.com/avatar.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class UserProfileResource extends JsonResource
{
    /**
     * @param \App\Models\UserProfile $resource
     */
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'avatar'    => $this->avatar,
            'phone'     => $this->phone,
            'birthdate' => $this->birthdate?->format('d-m-Y'),
            'gender'    => $this->gender,
            'address'   => $this->address,
            'user' => new UserShortResource($this->whenLoaded('user')),
        ];
    }
}
