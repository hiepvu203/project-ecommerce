<?php

declare(strict_types=1);

namespace App\Http\Resources\Short;

use App\Http\Resources\ShopResource;
use App\Http\Resources\ShopVerificationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Summary of ShopVerificationAdminResource
 * @property mixed $shop
 */

/**
 * @OA\Schema(
 *     schema="ShopVerificationAdminResource",
 *     type="object",
 *     @OA\Property(property="shop", ref="#/components/schemas/ShopResource"),
 *     @OA\Property(property="verification", ref="#/components/schemas/ShopVerificationResource"),
 *     @OA\Property(property="user", ref="#/components/schemas/UserShortResource")
 * )
 */
class ShopVerificationAdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'shop' => new ShopResource($this->whenLoaded('shop')),
            'verification' => new ShopVerificationResource($this),
            'user' => new UserShortResource(optional($this->shop)->owner)
        ];
    }
}
