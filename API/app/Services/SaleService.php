<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;

class SaleService
{
    public function __construct(
        protected SaleRepository $saleRepository
    ) {}

    public function create(array $data, $user): Sale
    {
        return DB::transaction(function () use ($data, $user) {
            $data['shop_id'] = $user->shop->id ?? null;
            $sale = $this->saleRepository->create($data);
            $sale->products()->sync($data['products'] ?? []);
            return $sale->load('products');
        });
    }

    public function update(Sale $sale, array $data): Sale
    {
        return DB::transaction(function () use ($sale, $data) {
            $this->saleRepository->update($sale, $data);
            $sale->products()->sync($data['products'] ?? []);
            return $sale->load('products');
        });
    }

    public function delete(Sale $sale): bool
    {
        $sale->products()->detach();
        return $this->saleRepository->delete($sale);
    }

    public function find(int $id): Sale
    {
        return $this->saleRepository->find($id);
    }

    public function allPaginated(int $perPage = 20)
    {
        return $this->saleRepository->allPaginated($perPage);
    }

    public function toggleActive(Sale $sale): Sale
    {
        $sale->active = !$sale->active;
        $sale->save();
        return $sale->refresh();
    }
}
