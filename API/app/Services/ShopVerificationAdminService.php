<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShopVerificationRepository;
use App\Models\Shop;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Enums\StatusEnum;
use App\Models\ShopVerification;

class ShopVerificationAdminService
{
    public function __construct(
        protected ShopVerificationRepository $shopVerificationRepository
    ) {}

    public function listPendingVerifications(int $perPage = 10)
    {
        return $this->shopVerificationRepository->getPendingVerifications($perPage);
    }

    public function approveShopVerification(int $shopId)
    {
        return DB::transaction(function () use ($shopId) {
            /** @var ShopVerification $shop */
            $shop = Shop::with('verification')->findOrFail($shopId);

            if (!$shop->verification || $shop->verification->status !== StatusEnum::PENDING->value) {
                return ['error' => 'Shop has not sent documents or has been processed.', 'code' => 422];
            }

            $updateData = [
                'status' => StatusEnum::APPROVED->value,
                'rejection_reason' => null,
                'verified_by' => Auth::id(),
                'verified_at' => Carbon::now(),
            ];

            $this->shopVerificationRepository->updateStatus($shop->verification, $updateData);

            $shop->update(['status' => StatusEnum::ACTIVE]);

            $owner = $shop->owner;
            $owner->update(['type' => User::TYPE_SHOP_OWNER]);

            $role = Role::where('name', User::TYPE_SHOP_OWNER)->firstOrFail();

            UserRole::updateOrCreate([
                'user_id' => $owner->id,
                'role_id' => $role->id,
                'shop_id' => $shop->id,
            ]);

            return $shop->load('owner');
        });
    }

    public function rejectShopVerification(int $shopId, array $data)
    {
        return DB::transaction(function () use ($shopId, $data) {
            $shop = Shop::with('verification')->findOrFail($shopId);

            if (!$shop->verification || $shop->verification->status !== StatusEnum::PENDING->value) {
                return ['error' => 'Shop has not sent documents or has been processed.', 'code' => 422];
            }

            $updateData = [
                'status' => StatusEnum::REJECTED->value,
                'rejection_reason' => $data['rejection_reason'],
                'verified_by' => Auth::id(),
                'verified_at' => Carbon::now(),
            ];

            $this->shopVerificationRepository->updateStatus($shop->verification, $updateData);

            $shop->update(['status' => StatusEnum::REJECTED]);

            return $shop->load('owner');
        });
    }
}
