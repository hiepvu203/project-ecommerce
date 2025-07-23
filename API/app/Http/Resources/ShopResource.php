<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'owner_id'        => $this->owner_id,
            'name'            => $this->name,
            'slug'            => $this->slug,
            'logo_url'        => $this->logo_url,
            'cover_image_url' => $this->cover_image_url,
            'description'     => $this->description,
            'address'         => $this->address,
            'phone'           => $this->phone,
            'email'           => $this->email,
            'shipping_config' => $this->shipping_config,
            'payment_methods' => $this->payment_methods,
            'status'          => $this->status,
            'created_at'      => $this->created_at?->format('d-m-Y'),
        ];
    }
}
