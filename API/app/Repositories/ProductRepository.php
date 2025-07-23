<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function findWithRelations(int $id): ?Product
    {
        return Product::with(['shop', 'images', 'variants', 'category'])->find($id);
    }

    public function getActiveProducts(array $filters, int $perPage = 15)
    {
        return Product::with(['shop', 'images', 'variants'])
            ->where('status', 'active')
            ->when($filters['category_id'] ?? null, fn($q) => $q->where('category_id', $filters['category_id']))
            ->when($filters['search'] ?? null, fn($q) => $q->where('name', 'ILIKE', '%' . $filters['search'] . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getShopProducts($shop, array $filters, int $perPage = 15)
    {
        return $shop->products()
            ->with(['images', 'variants', 'category'])
            ->when($filters['search'] ?? null, fn($q) => $q->where('name', 'ILIKE', '%' . $filters['search'] . '%'))
            ->when($filters['status'] ?? null, fn($q) => $q->where('status', $filters['status']))
            ->when($filters['category_id'] ?? null, fn($q) => $q->where('category_id', $filters['category_id']))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
