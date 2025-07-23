<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SystemCategoryRepository;
use App\Services\Upload\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SystemCategory;

class SystemCategoryService
{
    public function __construct(
        protected SystemCategoryRepository $categoryRepository,
        protected CloudinaryService $cloudinaryService
    ) {}

    public function listCategories(int $perPage = 15)
    {
        return $this->categoryRepository->getRootCategoriesWithChildren($perPage);
    }

    public function getCategory(int $id)
    {
        return $this->categoryRepository->findWithChildren($id);
    }

    public function createCategory(array $data)
    {
        $slug = $data['slug'] ?? Str::slug($data['name']);

        return $this->categoryRepository->create([
            ...$data,
            'slug' => $slug,
        ]);
    }

    public function updateCategory(SystemCategory $category, array $data, $imageFile = null)
    {
        $slug = $data['slug'] ?? Str::slug($data['name']);
        $this->categoryRepository->update($category, [
            ...$data,
            'slug' => $slug,
        ]);
        return $category->refresh();
    }

    public function findCategory(int $id): SystemCategory
    {
        return $this->categoryRepository->find($id);
    }
}
