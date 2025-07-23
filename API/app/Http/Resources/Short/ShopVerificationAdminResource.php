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
