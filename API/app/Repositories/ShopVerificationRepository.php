<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\StatusEnum;
use App\Models\ShopVerification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ShopVerificationRepository
{
    public function create(array $data): ShopVerification
    {
        return ShopVerification::create($data);
    }

    public function getPendingVerifications(int $perPage = 10): LengthAwarePaginator
    {
        return ShopVerification::with(['shop.owner'])
            ->where('status', StatusEnum::PENDING->value)
            ->latest()
            ->paginate($perPage);
    }

    public function updateStatus(ShopVerification $verification, array $data): bool
    {
        return $verification->update($data);
    }
}
