<?php

declare(strict_types= 1);

namespace App\Http\Controllers\API\v1\Shop;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\SubOrderService;
use Illuminate\Support\Facades\Auth;

class ShopOrderController extends Controller
{
    public function __construct(
        protected SubOrderService $subOrderService
    ) {}

    public function index()
    {
        $user = Auth::guard('api')->user();
        $shop = $user->shop;

        if (!$shop)
            return ApiResponse::fail(null, 'Shop not found', 404);

        $subOrders = $this->subOrderService->getShopSubOrders($shop->id);

        return ApiResponse::success($subOrders, 'Shop orders retrieved successfully.');
    }

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
