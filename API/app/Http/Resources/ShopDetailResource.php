<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ShopDetailResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=3),
 *     @OA\Property(property="owner_id", type="integer", example=15),
 *     @OA\Property(property="name", type="string", example="Cool Gadgets"),
 *     @OA\Property(property="slug", type="string", example="cool-gadgets"),
 *     @OA\Property(property="logo", type="string", format="url", example="https://example.com/logo.png"),
 *     @OA\Property(property="cover_image", type="string", format="url", example="https://example.com/cover.png"),
 *     @OA\Property(property="description", type="string", example="Best gadgets in town"),
 *     @OA\Property(property="address", type="string", example="123 Lê Lợi, Q1, TP.HCM"),
 *     @OA\Property(property="phone", type="string", example="0909123123"),
 *     @OA\Property(property="email", type="string", format="email", example="shop@example.com"),
 *     @OA\Property(property="shipping_config", type="object"),
 *     @OA\Property(property="payment_methods", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="created_at", type="string", format="date", example="01-01-2024"),
 *     @OA\Property(property="products", type="array", @OA\Items(ref="#/components/schemas/ProductResource"))
 * )
 */
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
