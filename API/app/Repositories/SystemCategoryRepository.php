<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SystemCategory;

class SystemCategoryRepository
{
    public function getRootCategoriesWithChildren(int $perPage = 15)
    {
        return SystemCategory::query()
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('order_position')
            ->paginate($perPage);
    }

    public function findWithChildren(int $id): ?SystemCategory
    {
        return SystemCategory::with('children')->findOrFail($id);
    }

    public function create(array $data): SystemCategory
    {
        return SystemCategory::create($data);
    }

    public function update(SystemCategory $category, array $data): bool
    {
        return $category->update($data);
    }

    public function find(int $id): ?SystemCategory
    {
        return SystemCategory::findOrFail($id);
    }
}
