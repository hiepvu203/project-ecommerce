<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Short\UserShortResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
