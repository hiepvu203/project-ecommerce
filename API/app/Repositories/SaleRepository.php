<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Sale;

class SaleRepository
{
    public function create(array $data): Sale
    {
        return Sale::create($data);
    }

    public function update(Sale $sale, array $data): Sale
    {
        $sale->update($data);
        return $sale->refresh();
    }

    public function delete(Sale $sale): ?bool
    {
        return $sale->delete();
    }

    public function find(int $id): ?Sale
    {
        return Sale::with('products')->findOrFail($id);
    }

    public function allPaginated(int $perPage = 20)
    {
        return Sale::with('products')->latest()->paginate($perPage);
    }
}
