<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use App\Models\SubOrder;
use App\Models\OrderItem;
use App\Models\User;

class OrderRepository
{
    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function createSubOrder(array $data): SubOrder
    {
        return SubOrder::create($data);
    }

    public function insertOrderItems(array $items): void
    {
        OrderItem::insert($items);
    }

    public function updateOrder(Order $order, array $data): bool
    {
        return $order->update($data);
    }

    public function findUserOrder(int $userId, int $orderId): ?Order
    {
        return Order::with(['subOrders.shop', 'subOrders.orderItems.product', 'subOrders.orderItems.variant'])
            ->where('user_id', $userId)
            ->where('id', $orderId)
            ->first();
    }

    public function findUserOrderById(int $userId, int $orderId): ?Order
    {
        return Order::where('user_id', $userId)->where('id', $orderId)->first();
    }
}
