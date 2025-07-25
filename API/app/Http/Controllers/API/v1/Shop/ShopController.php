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

/**
 * @OA\Tag(
 *     name="Shop",
 *     description="Shop-owner endpoints"
 * )
 */
class ShopController extends Controller
{
    public function __construct(
        protected ShopService $shopService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/shops/registrations",
     *     operationId="shopRegister",
     *     tags={"Shop"},
     *     summary="Register a new shop",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/RegisterShopRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Shop created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Complete shop information. Please verify documents to complete registration!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="shop", ref="#/components/schemas/ShopResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=409, description="Conflict")
     * )
     */
    public function register(RegisterShopRequest $request)
    {
        try {
            $shop = $this->shopService->register($request->validated(), Auth::user());
            return ApiResponse::success(['shop' => new ShopResource($shop)], 'Complete shop information. Please verify documents to complete registration!', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/my-shop",
     *     operationId="getMyShop",
     *     tags={"Shop"},
     *     summary="Get current user’s own shop",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Shop data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="shop", ref="#/components/schemas/ShopResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="No shop found")
     * )
     */
    public function getMyShop(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user->shop)
            return ApiResponse::fail(null, 'You do not own any shop.', 404);

        return ApiResponse::success(['shop' => new ShopResource($user->shop)], 'Your shop information.');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/my-shop",
     *     operationId="updateMyShop",
     *     tags={"Shop"},
     *     summary="Update the authenticated user’s shop",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/UpdateMyShopRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Updated shop information successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="shop", ref="#/components/schemas/ShopResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="No shop found")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/shops/{id}",
     *     operationId="shopShowPublic",
     *     tags={"Public"},
     *     summary="Public shop details (products included)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Public shop data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/ShopDetailResource"),
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/my-shop/locks",
     *     operationId="lockMyShop",
     *     tags={"Shop"},
     *     summary="Lock the current shop",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Locked",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Shop locked successfully.")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Already locked / no shop")
     * )
     */
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
