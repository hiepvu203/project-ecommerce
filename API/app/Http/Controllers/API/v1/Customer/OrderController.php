<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\PlaceOrderRequest;
use App\Helpers\ApiResponse;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}
    public function previewCheckout(CheckoutRequest $request)
    {
        try {
            $user = Auth::user();
            $data = $this->orderService->previewCheckout($user, $request->validated());
            return ApiResponse::success($data, 'Preview checkout successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }
    public function checkout(PlaceOrderRequest $request)
    {
        try {
            $user = Auth::user();
            $order = $this->orderService->checkout($user, $request->validated());
            return ApiResponse::success($order, 'Order placed successfully.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }

    public function show(int $id)
    {
        $user = Auth::user();

        $order = $this->orderService->getUserOrder($user->id, $id);

        if (!$order) {
            return ApiResponse::fail(null, 'Order not found', 404);
        }

        return ApiResponse::success(OrderResource::make($order));
    }

    public function cancel($id, Request $request)
    {
        try {
            $user = Auth::user();
            $this->orderService->cancelOrder($user, $id, $request->get('reason'));
            return ApiResponse::success(null, 'Order cancelled successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to cancel order', 500, null);
        }
    }
}
