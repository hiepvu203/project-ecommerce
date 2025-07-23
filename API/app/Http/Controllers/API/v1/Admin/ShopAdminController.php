<?php

declare(strict_types= 1);

namespace App\Http\Controllers\API\v1\Admin;

use App\Models\Shop;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\ShopService;

class ShopAdminController extends Controller
{
    public function __construct(
        protected ShopService $shopService
    ) {}

    public function unlock($shopId)
    {
        $shop = Shop::findOrFail($shopId);

        if ($shop->status === 'active')
            return ApiResponse::fail(null, 'Shop is already active.', 400);

        $this->shopService->unlockShop($shop);

        return ApiResponse::success($shop->refresh(), 'Shop unlocked successfully.');
    }
}
