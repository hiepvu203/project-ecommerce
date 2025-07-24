<?php

declare(strict_types= 1);

namespace App\Http\Controllers\API\v1\Admin;

use App\Models\Shop;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\ShopService;

/**
 * @OA\Tag(
 *     name="Admin / Shops",
 *     description="Admin-only shop management endpoints"
 * )
 */
class ShopAdminController extends Controller
{
    public function __construct(
        protected ShopService $shopService
    ) {}

    /**
     * Unlock a locked shop
     *
     * @OA\Post(
     *     path="/api/v1/admin/shops/{shopId}/unlocks",
     *     operationId="unlockShop",
     *     tags={"Admin / Shops"},
     *     summary="Unlock a shop that was previously locked",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="shopId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shop unlocked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Shop unlocked successfully."),
     *         )
     *     ),
     *     @OA\Response(response=400, description="Shop already active"),
     *     @OA\Response(response=404, description="Shop not found")
     * )
     */
    public function unlock($shopId)
    {
        $shop = Shop::findOrFail($shopId);

        if ($shop->status === 'active')
            return ApiResponse::fail(null, 'Shop is already active.', 400);

        $this->shopService->unlockShop($shop);
        $shop->refresh();
        return ApiResponse::success(null, 'Shop unlocked successfully.');
    }
}
