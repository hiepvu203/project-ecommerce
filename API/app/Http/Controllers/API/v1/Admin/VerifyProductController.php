<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Admin;

use App\Enums\StatusEnum;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifyRequest;
use App\Http\Resources\ProductDetailResource;
use App\Models\Product;

/**
 * @OA\Tag(
 *     name="Admin / Products",
 *     description="Admin-only product-verification endpoints"
 * )
 */
class VerifyProductController extends Controller
{
    /**
     * Approve a product
     *
     * @OA\Post(
     *     path="/api/v1/admin/products/{product}/approvals",
     *     operationId="adminApproveProduct",
     *     tags={"Admin / Products"},
     *     summary="Approve a product (DRAFT ➜ ACTIVE)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product approved",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product verification successful."),
     *             @OA\Property(property="data", ref="#/components/schemas/ProductDetailResource")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Product not found or already verified"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function approve(int $productId)
    {
        $product = Product::where('id', $productId)
            ->where('status', StatusEnum::DRAFT->value)
            ->first();

        if (!$product) {
            return ApiResponse::fail(null, 'Product does not exist or has been verified.', 400);
        }

        $product->update([
            'status' => StatusEnum::ACTIVE->value,
            'rejection_reason' => null,
        ]);

        return ApiResponse::success(['product' => new ProductDetailResource($product)], 'Product verification successful.');
    }

    /**
     * Reject a product
     *
     * @OA\Post(
     *     path="/api/v1/admin/products/{product}/rejections",
     *     operationId="adminRejectProduct",
     *     tags={"Admin / Products"},
     *     summary="Reject a product (DRAFT ➜ DRAFT with reason)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/VerifyRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product rejected",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product rejected successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/ProductDetailResource")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Product not found or already verified"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function reject(int $productId, VerifyRequest $request)
    {
        $product = Product::where('id', $productId)
            ->where('status', StatusEnum::DRAFT->value)
            ->first();

        if (!$product) {
            return ApiResponse::fail(null, 'Sản phẩm không tồn tại hoặc đã được phê duyệt.', 400);
        }

        $product->update([
            'status' => StatusEnum::DRAFT->value,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return ApiResponse::success(['product' => new ProductDetailResource($product)], 'Product rejected successfully.');
    }
}
