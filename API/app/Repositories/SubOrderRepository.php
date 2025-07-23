<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SubOrder;

class SubOrderRepository
{
    public function getShopSubOrders(int $shopId, int $perPage = 20)
    {
        return SubOrder::with(['order', 'order.user', 'orderItems.product', 'orderItems.variant'])
            ->where('shop_id', $shopId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findShopSubOrder(int $shopId, int $id): ?SubOrder
    {
        return SubOrder::with(['order', 'order.user', 'orderItems.product', 'orderItems.variant'])
            ->where('shop_id', $shopId)
            ->where('id', $id)
            ->first();
    }

    public function approveSubOrder(SubOrder $subOrder): SubOrder
    {
        $subOrder->status = 'approved';
        $subOrder->save();
        return $subOrder->refresh();
    }
}
