<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Services\SubOrderService;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Customer / Sub-Orders",
 *     description="Endpoints for customers to manage their sub-orders"
 * )
 */
class SubOrderController extends Controller
{
    public function __construct(
        protected SubOrderService $subOrderService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/customers/sub-orders/{id}/cancellations",
     *     operationId="customerCancelSubOrder",
     *     tags={"Customer / Sub-Orders"},
     *     summary="Cancel a sub-order as a customer",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="reason", type="string", example="Changed my mind")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sub-order cancelled",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Sub-order cancelled successfully.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Sub-order not found"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function cancel(int $id)
    {
        $user = Auth::user();
        $subOrder = SubOrder::findOrFail($id);

        try {
            $cancelled = $this->subOrderService->cancelByCustomer($subOrder, $user->id);
            return ApiResponse::success($cancelled, 'Sub-order cancelled successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/customers/purchased-products",
     *     operationId="customerPurchasedProducts",
     *     tags={"Customer / Sub-Orders"},
     *     summary="Retrieve list of products the customer has purchased",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of purchased products",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", example=99),
     *                     @OA\Property(property="product_name", type="string", example="Smart Watch X1"),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="price", type="number", format="float", example=1250000),
     *                     @OA\Property(property="sub_order_id", type="integer", example=10),
     *                     @OA\Property(property="purchased_at", type="string", format="date-time", example="2025-07-24T10:00:00Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function purchasedProducts(Request $request)
    {
        $user = $request->user();

        $subOrders = $user->subOrders()->with(['orderItems.product'])->get();

        $products = [];
        foreach ($subOrders as $subOrder) {
            foreach ($subOrder->orderItems as $orderItem) {
                if (!$orderItem->product) {
                    continue;
                }
                $products[] = [
                    'product_id'   => $orderItem->product->id,
                    'product_name' => $orderItem->product->name,
                    'quantity'     => $orderItem->quantity,
                    'price'        => $orderItem->price,
                    'sub_order_id' => $subOrder->id,
                    'purchased_at' => $subOrder->created_at,
                ];
            }
        }

        return ApiResponse::success($products,'Successfully!');
    }
}
