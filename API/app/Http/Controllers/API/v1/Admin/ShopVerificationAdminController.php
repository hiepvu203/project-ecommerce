<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectShopRequest;
use App\Http\Resources\Short\ShopVerificationAdminResource;
use App\Models\Shop;
use App\Models\ShopVerification;
use App\Services\ShopVerificationAdminService;
use Illuminate\Http\Request;

class ShopVerificationAdminController extends Controller
{
    public function __construct(
        protected ShopVerificationAdminService $shopVerificationAdminService
    ){}
    public function index()
    {
        return ApiResponse::success($this->shopVerificationAdminService->listPendingVerifications(), 'Shop list for verification.',200,[], ShopVerificationAdminResource::class);
    }

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
