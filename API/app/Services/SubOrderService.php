<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\StatusEnum;
use App\Repositories\SubOrderRepository;
use App\Models\SubOrder;

class SubOrderService
{
    public function __construct(
        protected SubOrderRepository $subOrderRepository
    ) {}

    public function getShopSubOrders(int $shopId, int $perPage = 20)
    {
        return $this->subOrderRepository->getShopSubOrders($shopId, $perPage);
    }

    public function findShopSubOrder(int $shopId, int $id): ?SubOrder
    {
        return $this->subOrderRepository->findShopSubOrder($shopId, $id);
    }

    public function approveSubOrder(SubOrder $subOrder): SubOrder
    {
        if ($subOrder->status !== StatusEnum::PENDING->value) {
            throw new \Exception('Only pending orders can be approved.', 400);
        }
        return $this->subOrderRepository->approveSubOrder($subOrder);
    }

    public function cancelByCustomer(SubOrder $subOrder, $userId): SubOrder
    {
        // Chỉ cho phép hủy nếu sub-order thuộc về user và chưa bị shop xử lý (ví dụ: chưa shipping/completed)
        if ($subOrder->order->user_id !== $userId) {
            throw new \Exception('Access denied.', 403);
        }
        if ($subOrder->status !== 'pending') {
            throw new \Exception('Cannot cancel after shop has processed the order.', 400);
        }
        if ($subOrder->status_customer === 'cancelled') {
            throw new \Exception('Order already cancelled.', 400);
        }

        $subOrder->status_customer = 'cancelled';
        $subOrder->save();

        // (Tùy chọn) Gửi notification cho shop

        return $subOrder->refresh();
    }
}
