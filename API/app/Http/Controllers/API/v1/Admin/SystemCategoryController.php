<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SystemCategoryRequest;
use App\Http\Requests\Admin\UpdateSystemCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\SystemCategoryService;
use Illuminate\Http\Request;

class SystemCategoryController extends Controller
{
    public function __construct(protected SystemCategoryService $categoryService) {}

    public function index()
    {;
        return ApiResponse::success($this->categoryService->listCategories(),'Categories retrieved successfully.', 200, [], CategoryResource::class);
    }

    public function show(int $id)
    {
        $category = $this->categoryService->getCategory($id);
        return ApiResponse::success(new CategoryResource($category),'Category retrieved successfully.');
    }

    public function store(SystemCategoryRequest $request)
    {
        $data = $request->validated();
        $category = $this->categoryService->createCategory($data);
        return ApiResponse::success($category, 'Category created successfully.', 201);
    }

    public function update(UpdateSystemCategoryRequest $request, int $id)
    {
        $category = $this->categoryService->findCategory($id);
        $data = $request->validated();
        $updatedCategory = $this->categoryService->updateCategory($category, $data);

        return ApiResponse::success(new CategoryResource($updatedCategory), 'Category updated successfully.');
    }
}
