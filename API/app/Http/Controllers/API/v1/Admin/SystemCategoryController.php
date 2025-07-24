<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SystemCategoryRequest;
use App\Http\Requests\Admin\UpdateSystemCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\SystemCategoryService;

/**
 * @OA\Tag(
 *     name="Admin / System Categories",
 *     description="Admin-only endpoints to manage system-wide categories"
 * )
 */
class SystemCategoryController extends Controller
{
    public function __construct(protected SystemCategoryService $categoryService) {}

    /**
     * List all system categories
     *
     * @OA\Get(
     *     path="/api/v1/admin/system-categories",
     *     operationId="listSystemCategories",
     *     tags={"Admin / System Categories"},
     *     summary="Get paginated list of system categories",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Categories retrieved successfully."),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CategoryResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {;
        return ApiResponse::success($this->categoryService->listCategories(),'Categories retrieved successfully.', 200, [], CategoryResource::class);
    }

    /**
     * Detail a single system category
     *
     * @OA\Get(
     *     path="/api/v1/admin/system-categories/{id}",
     *     operationId="getSystemCategory",
     *     tags={"Admin / System Categories"},
     *     summary="Get one system category",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category retrieved successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/CategoryResource")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function show(int $id)
    {
        $category = $this->categoryService->getCategory($id);
        return ApiResponse::success(new CategoryResource($category),'Category retrieved successfully.');
    }

    /**
     * Create a new system category
     *
     * @OA\Post(
     *     path="/api/v1/admin/system-categories",
     *     operationId="storeSystemCategory",
     *     tags={"Admin / System Categories"},
     *     summary="Create a new system category",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SystemCategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category created successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/CategoryResource")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(SystemCategoryRequest $request)
    {
        $data = $request->validated();
        $category = $this->categoryService->createCategory($data);
        return ApiResponse::success($category, 'Category created successfully.', 201);
    }

    /**
     * Update an existing system category
     *
     * @OA\Put(
     *     path="/api/v1/admin/system-categories/{id}",
     *     operationId="updateSystemCategory",
     *     tags={"Admin / System Categories"},
     *     summary="Update a system category",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSystemCategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/CategoryResource")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Category not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateSystemCategoryRequest $request, int $id)
    {
        $category = $this->categoryService->findCategory($id);
        $data = $request->validated();
        $updatedCategory = $this->categoryService->updateCategory($category, $data);

        return ApiResponse::success(new CategoryResource($updatedCategory), 'Category updated successfully.');
    }
}
