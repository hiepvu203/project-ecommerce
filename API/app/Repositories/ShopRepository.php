<?php

declare(strict_types= 1);

namespace App\Repositories;

use App\Models\Shop;
use App\Models\User;

class ShopRepository {
    public function create(array $data): Shop {
        return Shop::create($data);
    }

    public function update(Shop $shop, array $data): Shop
    {
        $shop->update($data);
        return $shop->fresh();
    }

    public function findByOwner(User $user): ?Shop
    {
        return $user->shop()->first();
    }

    public function findBySlug(string $slug): ?Shop
    {
        return Shop::where('slug', $slug)->first();
    }
}
