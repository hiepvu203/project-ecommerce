<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectShopRequest;
use App\Http\Resources\Short\ShopVerificationAdminResource;
use App\Models\Shop;
use App\Services\ShopVerificationAdminService;

/**
 * @OA\Tag(
 *     name="Admin / Shop Verifications",
 *     description="Admin-only endpoints to approve or reject shop verification submissions"
 * )
 */
class ShopVerificationAdminController extends Controller
{
    public function __construct(
        protected ShopVerificationAdminService $shopVerificationAdminService
    ){}

    /**
     * List pending shop verifications
     *
     * @OA\Get(
     *     path="/api/v1/admin/shops/verifications",
     *     operationId="listPendingShopVerifications",
     *     tags={"Admin / Shop Verifications"},
     *     summary="Get list of shops waiting for verification",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Shop list for verification",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Shop list for verification."),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ShopVerificationAdminResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return ApiResponse::success($this->shopVerificationAdminService->listPendingVerifications(), 'Shop list for verification.',200,[], ShopVerificationAdminResource::class);
    }

    /**
     * Approve a shop verification
     *
     * @OA\Post(
     *     path="/api/v1/admin/shops/{shopId}/approvals",
     *     operationId="approveShop",
     *     tags={"Admin / Shop Verifications"},
     *     summary="Approve shop verification",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="shopId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shop verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Shop verified successfully!")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Cannot approve"),
     *     @OA\Response(response=404, description="Shop not found")
     * )
     */
    public function approve(int $shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $this->authorize('approve', $shop);
        $result = $this->shopVerificationAdminService->approveShopVerification($shopId);

        if (is_array($result) && isset($result['error'])){
            return ApiResponse::fail(null, $result['error'], 400);
        }

        return ApiResponse::success($result, 'Shop verified successfully!');
    }

    /**
     * Reject a shop verification
     *
     * @OA\Post(
     *     path="/api/v1/admin/shops/{shopId}/rejections",
     *     operationId="rejectShop",
     *     tags={"Admin / Shop Verifications"},
     *     summary="Reject shop verification with reason",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="shopId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RejectShopRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shop rejected successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Shop rejected successfully!")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Cannot reject"),
     *     @OA\Response(response=404, description="Shop not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function reject(int $shopId, RejectShopRequest $request)
    {
        $shop = Shop::findOrFail($shopId);
        $this->authorize('approve', $shop);

        $result = $this->shopVerificationAdminService->rejectShopVerification($shopId, $request->validated());

        if (is_array($result) && isset($result['error']))
            return ApiResponse::fail(null, $result['error'], 400);

        return ApiResponse::success($result, 'Shop rejected successfully!');
    }
}
