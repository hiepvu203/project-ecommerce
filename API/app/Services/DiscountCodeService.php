<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Exceptions\ForbiddenException;
use App\Repositories\DiscountCodeRepository;
use App\Models\DiscountCode;
use App\Models\User;    

class DiscountCodeService
{
    public function __construct(
        protected DiscountCodeRepository $discountCodeRepository
    ) {}

    public function create(array $data, $user): DiscountCode
    {
        $shopId = null;

        if ($user->type === 'shop_owner')
            $shopId = $user->shop->id ?? null;

        $data['shop_id'] = $shopId;
        return $this->discountCodeRepository->create($data);
    }

    public function update(DiscountCode $discount, array $data, User $user): DiscountCode
    {
        // Admin can only update the exchange code
        if ($user->type === 'admin') {
            if ($discount->shop_id !== null)
                throw new ForbiddenException('Admin cannot update shop owner discount codes.', 403);

            return $this->discountCodeRepository->update($discount, $data);
        }

        // Shop owner can only update their own shop code
        if ($user->type === 'shop_owner') {
            if ($discount->shop_id !== $user->shop->id) {
                throw new ForbiddenException('You do not have permission to update this discount code.', 403);
            }

            // Code renaming is not allowed
            unset($data['code']);

            // check start at
            $now = now();
            if ($discount->start_at > $now) {
                // Not started: allows updating field (except code)
                return $this->discountCodeRepository->update($discount, $data);
            } else {
                // Started: only allows updating end date and increasing number of uses
                $allowed = [];
                if (isset($data['end_at'])) $allowed['end_at'] = $data['end_at'];
                if (isset($data['usage_limit']) && $data['usage_limit'] > $discount->usage_limit) {
                    $allowed['usage_limit'] = $data['usage_limit'];
                }
                if (empty($allowed)) {
                    throw new BusinessException('You can only update end date or increase usage limit after the code has started.', 400);
                }
                return $this->discountCodeRepository->update($discount, $allowed);
            }
        }
        // return $this->discountCodeRepository->update($discount, $data);
        throw new ForbiddenException('You do not have permission to update this discount code.', 403);
    }

    public function delete(DiscountCode $discount, User $user): bool
    {
        // Admin can only delete the exchange code
        if ($user->type === 'admin') {
            if ($discount->shop_id !== null) {
                throw new ForbiddenException('Admin cannot delete shop owner discount codes.', 403);
            }
            return $this->discountCodeRepository->delete($discount);
        }

        // Shop owner can only delete their own shop code
        if ($user->type === 'shop_owner') {
            if ($discount->shop_id !== $user->shop->id) {
                throw new ForbiddenException('You do not have permission to delete this discount code.', 403);
            }
        return $this->discountCodeRepository->delete($discount);
    }

    throw new ForbiddenException('Unauthorized.', 403);
    }

    public function find(int $id): DiscountCode
    {
        return $this->discountCodeRepository->find($id);
    }

    public function allPaginated(User $user, int $perPage = 15)
    {
        $query = DiscountCode::query();
        if ($user->shop) {
            $query->where('shop_id', $user->shop->id);
        }

        return $query->latest()->paginate($perPage);
    }
}
