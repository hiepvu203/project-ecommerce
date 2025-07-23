<?php

namespace App\Http\Controllers\API\v1\Customer;

use App\Services\SubOrderService;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubOrderController extends Controller
{
    public function __construct(
        protected SubOrderService $subOrderService
    ) {}

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
