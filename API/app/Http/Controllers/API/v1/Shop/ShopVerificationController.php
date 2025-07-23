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


class ShopVerificationController extends Controller
{
    public function __construct(
        protected CloudinaryService $cloudinaryService,
        protected ShopVerificationService $shopVerificationService,
    ) {}

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
