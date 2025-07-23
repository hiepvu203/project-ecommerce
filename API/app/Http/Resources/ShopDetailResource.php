<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopDetailResource extends JsonResource
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
            'logo'            => $this->logo,
            'cover_image'     => $this->cover_image,
            'description'     => $this->description,
            'address'         => $this->address,
            'phone'           => $this->phone,
            'email'           => $this->email,
            'shipping_config' => $this->shipping_config,
            'payment_methods' => $this->payment_methods,
            'status'          => $this->status,
            'created_at'      => $this->created_at?->format('d-m-Y'),

            'products'        => ProductResource::collection(
                $this->whenLoaded('products')
            ),
        ];
    }
}
