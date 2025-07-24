<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateDiscountCodeRequest;
use App\Http\Requests\Admin\DiscountCodeUpdateRequest;
use App\Http\Resources\DiscountCodeResource;
use App\Models\DiscountCode;
use App\Services\DiscountCodeService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Admin / Discount Codes (Mã giảm giá)",
 *     description="Endpoint dành cho quản lý mã giảm giá"
 * )
 */
class DiscountCodeController extends Controller
{
    public function __construct(
        protected DiscountCodeService $discountCodeService
    ) {}

    /**
     * Create a new discount code
     *
     * @OA\Post(
     *     path="/api/v1/admin/discount-codes",
     *     operationId="storeDiscountCode",
     *     tags={"Admin / Discount Codes"},
     *     summary="Tạo mã giảm giá mới",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateDiscountCodeRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Discount code created successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/DiscountCodeResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(CreateDiscountCodeRequest $request)
    {
        $user = Auth::guard('api')->user();
        $discount = $this->discountCodeService->create($request->validated(), $user);
        return ApiResponse::success($discount, 'Discount code created successfully!', 201, [], DiscountCodeResource::class);
    }

    /**
     * List all discount codes
     *
     * @OA\Get(
     *     path="/api/v1/admin/discount-codes",
     *     operationId="listDiscountCodes",
     *     tags={"Admin / Discount Codes"},
     *     summary="Get paginated list of discount codes",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Discount code reverted successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/DiscountCodeResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        $codes = $this->discountCodeService->allPaginated($user);
        return ApiResponse::success($codes,'Discount code reverted successfully!',200, [], DiscountCodeResource::class);
    }

    /**
     * Show one discount code
     *
     * @OA\Get(
     *     path="/api/v1/admin/discount-codes/{id}",
     *     operationId="showDiscountCode",
     *     tags={"Admin / Discount Codes"},
     *     summary="Get single discount code details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Discount code reverted successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="code", ref="#/components/schemas/DiscountCodeResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Discount code not found")
     * )
     */
    public function show($id)
    {
        $code = $this->discountCodeService->find($id);
        return ApiResponse::success(['code' => new DiscountCodeResource($code)],'Discount code reverted successfully!',200);
    }

    /**
     * Update a discount code
     *
     * @OA\Put(
     *     path="/api/v1/admin/discount-codes/{id}",
     *     operationId="updateDiscountCode",
     *     tags={"Admin / Discount Codes"},
     *     summary="Update an existing discount code",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DiscountCodeUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Discount code updated successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="code", ref="#/components/schemas/DiscountCodeResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Discount code not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(DiscountCodeUpdateRequest $request, DiscountCode $id)
    {
        $user = Auth::guard('api')->user();
        $updateCode = $this->discountCodeService->update($id, $request->validated(), $user);
        return ApiResponse::success(['code' => new DiscountCodeResource($updateCode)],'Discount code updated successfully!',200);
    }

    /**
     * Delete a discount code
     *
     * @OA\Delete(
     *     path="/api/v1/admin/discount-codes/{id}",
     *     operationId="deleteDiscountCode",
     *     tags={"Admin / Discount Codes"},
     *     summary="Delete a discount code",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Discount code deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Discount code not found")
     * )
     */
    public function destroy(DiscountCode $id)
    {
        $user = Auth::guard('api')->user();
        $this->discountCodeService->delete($id, $user);
        return ApiResponse::success(null, 'Discount code deleted successfully.');
    }
}
