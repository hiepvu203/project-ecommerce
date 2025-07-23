<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Shop;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\RegisterShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Shop\UpdateMyShopRequest;
use App\Http\Resources\ShopDetailResource;
use App\Services\ShopService;

class ShopController extends Controller
{
    public function __construct(
        protected ShopService $shopService
    ) {}

    public function register(RegisterShopRequest $request)
    {
        try {
            $shop = $this->shopService->register($request->validated(), Auth::user());
            return ApiResponse::success(['shop' => new ShopResource($shop)], 'Complete shop information. Please verify documents to complete registration!', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }

    public function getMyShop(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user->shop)
            return ApiResponse::fail(null, 'You do not own any shop.', 404);

        return ApiResponse::success(['shop' => new ShopResource($user->shop)], 'Your shop information.');
    }

    public function update(UpdateMyShopRequest $request)
    {
        $user = Auth::user();
        /** @var Shop $shop */
        $shop = $user->shop;

        if (!$shop)
            return ApiResponse::fail(null, 'You do not own any shop.', 404);

        $shop = $this->shopService->update($request->validated(), $shop, $user);

        return ApiResponse::success(['shop' => new ShopResource($shop)], 'Updated shop information successfully.');
    }

    public function showPublic(int $id)
    {
        try {
            $shop = Shop::with(['products.images', 'products.variants'])->find($id);

            if (!$shop)
                return ApiResponse::fail(null, 'Shop not found or does not exist.', 404);

            return ApiResponse::success(new ShopDetailResource($shop), 'Shop details retrieved successfully.');
        } catch (\Throwable $th) {
            return ApiResponse::error('Failed to return a specific shop.', 500, null);
        }
    }

    public function lock()
    {
        $user = Auth::user();
        /** @var Shop $shop */
        $shop = $user->shop;

        if (!$shop)
            return ApiResponse::fail(null, 'You do not own any shop.', 404);

        if ($shop->status === 'locked')
            return ApiResponse::fail(null, 'Shop is already locked.', 400);

        $shop = $this->shopService->lockShop($shop, $user);

        return ApiResponse::success($shop, 'Shop locked successfully.');
    }
}
