<?php

declare(strict_types= 1);

namespace App\Http\Controllers\API\v1\Shop;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\SubOrderService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Shop / Orders",
 *     description="Endpoints for shop owners to manage their sub-orders"
 * )
 */
class ShopOrderController extends Controller
{
    public function __construct(
        protected SubOrderService $subOrderService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/my-shop/orders",
     *     operationId="shopOrdersIndex",
     *     tags={"Shop / Orders"},
     *     summary="List all sub-orders belonging to the current shop",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of shop sub-orders",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Shop orders retrieved successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Shop not found")
     * )
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        $shop = $user->shop;

        if (!$shop)
            return ApiResponse::fail(null, 'Shop not found', 404);

        $subOrders = $this->subOrderService->getShopSubOrders($shop->id);

        return ApiResponse::success($subOrders, 'Shop orders retrieved successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/my-shop/orders/{id}",
     *     operationId="shopOrderShow",
     *     tags={"Shop / Orders"},
     *     summary="Get detail of a single sub-order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sub-order detail",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Shop order detail retrieved successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Order not found or access denied")
     * )
     */
    public function show(int $id){
        $user = Auth::user();
        $shop = $user->shop;

        if (!$shop) {
            return ApiResponse::fail(null, 'Shop not found.', 404);
        }

        $subOrder = $this->subOrderService->findShopSubOrder($shop->id, $id);

        if (!$subOrder) {
            return ApiResponse::fail(null, 'Order not found or access denied.', 404);
        }

        return ApiResponse::success($subOrder, 'Shop order detail retrieved successfully.');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/my-shop/orders/{id}/approvals",
     *     operationId="shopOrderApprove",
     *     tags={"Shop / Orders"},
     *     summary="Approve a sub-order",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order approved",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order approved successfully.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Order not found or access denied"),
     *     @OA\Response(response=400, description="Business-rule error")
     * )
     */
    public function approve(int $id){
        $user = Auth::guard('api')->user();
        $shop = $user->shop;

        if (!$shop)
            return ApiResponse::fail(null, 'Shop not found', 404);

        $subOrder = $this->subOrderService->findShopSubOrder($shop->id, $id);

        if (!$subOrder) {
            return ApiResponse::fail(null, 'Order not found or access denied.', 404);
        }

        try {
            $approvedSubOrder = $this->subOrderService->approveSubOrder($subOrder);
            // (Tùy chọn) Gửi notification
            return ApiResponse::success($approvedSubOrder, 'Order approved successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }
}
