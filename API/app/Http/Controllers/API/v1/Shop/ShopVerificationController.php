<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Shop;

use App\Enums\StatusEnum;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\SubmitShopVerificationRequest;
use App\Services\Upload\CloudinaryService;
use App\Services\Upload\ShopVerificationService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Shop / Verification",
 *     description="Shop-owner document-verification endpoints"
 * )
 */
class ShopVerificationController extends Controller
{
    public function __construct(
        protected CloudinaryService $cloudinaryService,
        protected ShopVerificationService $shopVerificationService,
    ) {}

     /**
     * @OA\Post(
     *     path="/api/v1/shops/verifications/documents",
     *     operationId="submitShopVerification",
     *     tags={"Shop / Verification"},
     *     summary="Submit verification documents for the shop",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/SubmitShopVerificationRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Documents submitted",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Submit verification documents successfully!")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Shop not registered"),
     *     @OA\Response(response=422, description="Already pending / validation failed")
     * )
     */
    public function submit(SubmitShopVerificationRequest $request)
    {
        $user = Auth::guard('api')->user();
        $shop = $user->shop;

        if(!$shop)
            return ApiResponse::fail(null, 'You have not registered shop!', 403);

        if($shop->verification && $shop->verification->status === StatusEnum::PENDING->value)
            return ApiResponse::fail(null, 'Verification documents sent. Please wait for approval!', 422);

        $verification = $this->shopVerificationService->submitVerification($user, $request->all());

        return ApiResponse::success($verification, 'Submit verification documents successfully!', 201);
    }
}
