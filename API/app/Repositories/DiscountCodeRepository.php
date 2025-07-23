<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\DiscountCode;

class DiscountCodeRepository
{
    public function create(array $data): DiscountCode
    {
        return DiscountCode::create($data);
    }

    public function update(DiscountCode $discount, array $data): DiscountCode
    {
        $discount->update($data);
        return $discount->refresh();
    }

    public function delete(DiscountCode $discount): ?bool
    {
        return $discount->delete();
    }

    public function find(int $id): ?DiscountCode
    {
        return DiscountCode::findOrFail($id);
    }
}
