<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\PlaceOrderRequest;
use App\Helpers\ApiResponse;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Customer / Orders",
 *     description="Order management for logged-in customers"
 * )
 */
class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/customers/orders/previews",
     *     tags={"Customer / Order"},
     *     summary="Preview checkout totals, shipping & discounts",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CheckoutRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preview data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Preview checkout successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function previewCheckout(CheckoutRequest $request)
    {
        try {
            $user = Auth::user();
            $data = $this->orderService->previewCheckout($user, $request->validated());
            return ApiResponse::success($data, 'Preview checkout successfully');
        } catch (\Exception $e) {
            return ApiResponse::fail(null, $e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/customers/orders",
     *     tags={"Customer / Orders"},
     *     summary="Place the order",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlaceOrderRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order placed successfully."),
     *             @OA\Property(property="data")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/customers/orders/{id}",
     *     tags={"Customer / Orders"},
     *     summary="Get single order details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/SubOrderResource"),
     *         )
     *     ),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function show(int $id)
    {
        $user = Auth::user();

        $order = $this->orderService->getUserOrder($user->id, $id);

        if (!$order) {
            return ApiResponse::fail(null, 'Order not found', 404);
        }

        return ApiResponse::success(OrderResource::make($order));
    }
}
