<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\User;
use App\Enums\StatusEnum;
use App\Exceptions\BusinessException;
use App\Models\DiscountCode;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository
    ) {}

    protected function calculateDiscount(?string $discountCode, float $subTotal, User $user):array
    {
        if(! $discountCode)
        {
            return [
                'discount' => 0,
                'discount_code' => null,
                'discount_message' => null,
                'discount_id' => null
            ];
        }

        $discount = DiscountCode::where('code', $discountCode)
            ->where('active', true)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->first();

        if (!$discount)
            return [
                'discount' => 0,
                'discount_code' => $discountCode,
                'discount_message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.',
                'discount_id' => null,
            ];

        // check the total usage count of the whole system
        if ($discount->usage_limit !== null && $discount->used >= $discount->usage_limit) {
            return [
                'discount' => 0,
                'discount_code' => $discountCode,
                'discount_message' => 'Mã giảm giá đã hết lượt sử dụng.',
                'discount_id' => $discount->id
            ];
        }

        // check the number of uses for the user
        $userUsed = Order::where('user_id', $user->id)
            ->where('discount_code', $discountCode)
            ->exists();

        if ($userUsed)
            throw new BusinessException('Bạn đã sử dụng mã này ', 400);

        if ($userUsed) {
            return [
                'discount' => 0,
                'discount_code' => $discountCode,
                'discount_message' => 'Bạn đã sử dụng mã này rồi.',
                'discount_id' => $discount->id,
            ];
        }
        // check the minimum simple condition
        if ($subTotal < $discount->min_order_amount){
            return [
                'discount' => 0,
                'discount_code' => $discountCode,
                'discount_message' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã giảm giá.',
                'discount_id' => $discount->id
            ];
        }

        // calculate
        if ($discount->type === 'percent') {
            $discountValue = $subTotal * ($discount->value / 100);
        } else {
            $discountValue = $discount->value;
        }

        // do not exceed the total amount.
        $discountValue = min($discountValue, $subTotal);

        return [
            'discount' => $discountValue,
            'discount_code' => $discountCode,
            'discount_message' => "Áp dụng mã thành công!",
            'discount_id' => $discount->id,
        ];
    }

    private function getSelectedCartItems(User $user, array $itemIds): Collection
    {
        $cart = $user->cart;
        if (!$cart || $cart->items()->count() === 0) {
            throw new \Exception('Cart is empty!', 400);
        }

        $items = $cart->items()
                    ->with(['product', 'variant'])
                    ->whereIn('id', $itemIds)
                    ->get();

        if ($items->isEmpty()) {
            throw new \Exception('No valid cart items selected!', 400);
        }

        // (tuỳ chọn) Kiểm tra nếu user gửi id không thuộc cart của mình
        $invalid = collect($itemIds)->diff($items->pluck('id'));
        if ($invalid->isNotEmpty()) {
            throw new \Exception('Some cart items do not belong to your cart.', 400);
        }

        return $items;
    }
    public function previewCheckout(User $user, array $requestData): array
    {
        $cartItems = $this->getSelectedCartItems($user, $requestData['cart_item_ids']);
        $grouped = $cartItems->groupBy(fn($item) => $item->product->shop_id);

        $orderSubtotal = 0;
        $orderShippingFee = 0;
        $products = [];

        foreach ($grouped as $shopId => $items) {
            $shop = $items->first()->product->shop;
            $subTotal = 0;
            $shippingFee = $shop->shipping_config['fee'] ?? 0;

            foreach ($items as $item) {
                $itemTotal = $item->price_at_added * $item->quantity;
                $products[] = [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'variant' => $item->variant->value ?? null,
                    'price' => $item->price_at_added,
                    'quantity' => $item->quantity,
                    'total_price' => $itemTotal,
                    'shop_id' => $shopId,
                    'shop_name' => $shop->name,
                ];
                $subTotal += $itemTotal;
            }

            $orderSubtotal += $subTotal;
            $orderShippingFee += $shippingFee;
        }

        $orderDiscount = $this->calculateDiscount($requestData['discount_code'] ?? null, $orderSubtotal, $user);

        $orderTotal = $orderSubtotal + $orderShippingFee - $orderDiscount['discount'];

        return [
            'subtotal' => $orderSubtotal,
            'shipping_fee' => $orderShippingFee,
            'discount' => $orderDiscount['discount'],
            'discount_code' => $orderDiscount['discount_code'],
            'discount_message' => $orderDiscount['discount_message'],
            'total' => $orderTotal,
            'products' => $products,
            'shipping_address' => $requestData['shipping_address'] ?? null,
            'billing_address' => $requestData['billing_address'] ?? null,
            'payment_method' => $requestData['payment_method'] ?? null,
            'shipping_method' => $requestData['shipping_method'] ?? null,
        ];
    }

    public function checkout(User $user, array $requestData): Order
    {
        if ($user->type === User::TYPE_SHOP_OWNER && $user->shop) {
            foreach ($this->getSelectedCartItems($user, $requestData['cart_item_ids']) as $item) {
                if ($item->product && $item->product->shop_id === $user->shop->id) {
                    throw new \Exception('Shop owner cannot order products from their own shop.', 403);
                }
            }
        }

        return DB::transaction(function () use ($user, $requestData) {
            $cartItems = $this->getSelectedCartItems($user, $requestData['cart_item_ids']);
            $grouped   = $cartItems->groupBy(fn ($item) => $item->product->shop_id);

            $orderSubtotal    = 0;
            $orderShippingFee = 0;
            $orderItemsData   = [];
            $subOrdersData    = [];

            foreach ($grouped as $shopId => $items) {
                $shop        = $items->first()->product->shop;
                $shippingFee = $shop->shipping_config['fee'] ?? 0;
                $subTotal    = 0;

                foreach ($items as $item) {
                    $itemTotal = $item->price_at_added * $item->quantity;
                    $orderItemsData[] = [
                        'order_id'      => 0,
                        'sub_order_id'  => 0,
                        'product_id'    => $item->product_id,
                        'shop_id'       => $shopId,
                        'variant_id'    => $item->variant_id,
                        'product_name'  => $item->product->name,
                        'variant_name'  => $item->variant->value ?? null,
                        'price'         => $item->price_at_added,
                        'quantity'      => $item->quantity,
                        'total_price'   => $itemTotal,
                        'created_at'    => now(),
                    ];
                    $subTotal += $itemTotal;
                }

                $subOrdersData[] = [
                    'shop_id'      => $shopId,
                    'subtotal'     => $subTotal,
                    'shipping_fee' => $shippingFee,
                    'commission'   => 0,
                    'total'        => $subTotal + $shippingFee,
                    'status'       => StatusEnum::PENDING->value,
                ];

                $orderSubtotal    += $subTotal;
                $orderShippingFee += $shippingFee;
            }

            // Áp dụng mã giảm giá
            $discount = $this->calculateDiscount($requestData['discount_code'] ?? null, $orderSubtotal, $user);

            if ($discount['discount'] > 0 && $discount['discount_id']) {
                DiscountCode::where('id', $discount['discount_id'])
                            ->where('usage_limit', '>', 0)
                            ->decrement('usage_limit');
            }

            // Tạo Order & Sub-Orders
            $order = $this->orderRepository->createOrder([
                'order_number'   => strtoupper(Str::random(10)),
                'user_id'        => $user->id,
                'subtotal'       => $orderSubtotal,
                'shipping_fee'   => $orderShippingFee,
                'discount'       => $discount['discount'],
                'discount_code'  => $discount['discount_code'],
                'total'          => $orderSubtotal + $orderShippingFee - $discount['discount'],
                'payment_method' => $requestData['payment_method'],
                'payment_status' => StatusEnum::PENDING->value,
                'shipping_method'=> $requestData['shipping_method'],
                'shipping_address'=> $requestData['shipping_address'],
                'billing_address' => $requestData['billing_address'],
                'status'         => StatusEnum::PENDING->value,
                'notes'          => $requestData['notes'] ?? null,
            ]);

            foreach ($subOrdersData as $subOrderData) {
                $subOrder = $this->orderRepository->createSubOrder([
                    ...$subOrderData,
                    'order_id' => $order->id,
                ]);

                foreach ($orderItemsData as &$item) {
                    if ($item['shop_id'] === $subOrder->shop_id) {
                        $item['order_id']     = $order->id;
                        $item['sub_order_id'] = $subOrder->id;
                    }
                }
                unset($item);
            }

            $this->orderRepository->insertOrderItems($orderItemsData);

            // Xoá các cart item đã mua
            $cartItems->each->delete();

            return $order->load('subOrders', 'subOrders.shop');
        });
    }

    public function getUserOrder(int $userId, int $orderId)
    {
        return $this->orderRepository->findUserOrder($userId, $orderId);
    }

    public function cancelOrder(User $user, int $orderId, ?string $reason = null):bool
    {
        $order = $this->orderRepository->findUserOrderById($user->id, $orderId);

        if (!$order) {
            throw new \Exception('Order not found', 404);
        }

        if ($order->status !== StatusEnum::PENDING->value) {
            throw new \Exception('Only pending orders can be cancelled', 422);
        }

        return DB::transaction(function () use ($order, $reason) {
            // Cập nhật trạng thái đơn hàng và các sub-orders
            $order->status = 'cancelled';
            $order->save();

            foreach ($order->subOrders as $sub) {
                $sub->status = 'cancelled';
                $sub->cancellation_reason = $reason ?? 'Customer cancelled';
                $sub->save();
            }

            // 2. Hoàn lại stock
            foreach ($order->items as $item) {
                optional($item->product)->increment('stock', $item->quantity);
            }

            // 3. Hoàn lại usage nếu có mã giảm giá
            if ($order->discount_id) {
                DiscountCode::where('id', $order->discount_id)
                    ->where('usage_limit', '>', 0)
                    ->increment('usage_limit');
            }

            return true;
        });
    }
}
