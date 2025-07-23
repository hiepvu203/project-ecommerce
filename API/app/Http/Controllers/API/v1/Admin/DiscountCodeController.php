<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateDiscountCodeRequest;
use App\Http\Requests\Admin\DiscountCodeUpdateRequest;
use App\Http\Resources\DiscountCodeResource;
use App\Models\DiscountCode;
use App\Models\User;
use App\Services\DiscountCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountCodeController extends Controller
{
    public function __construct(
        protected DiscountCodeService $discountCodeService
    ) {}

    public function store(CreateDiscountCodeRequest $request)
    {
        $user = Auth::guard('api')->user();
        $discount = $this->discountCodeService->create($request->validated(), $user);
        return ApiResponse::success($discount, 'Discount code created successfully!', 201, [], DiscountCodeResource::class);
    }

    public function index()
    {
        $user = Auth::guard('api')->user();
        $codes = $this->discountCodeService->allPaginated($user);
        return ApiResponse::success($codes,'Discount code reverted successfully!',200, [], DiscountCodeResource::class);
    }

    public function show($id)
    {
        $code = $this->discountCodeService->find($id);
        return ApiResponse::success(['code' => new DiscountCodeResource($code)],'Discount code reverted successfully!',200);
    }

    public function update(DiscountCodeUpdateRequest $request, DiscountCode $id)
    {
        $user = Auth::guard('api')->user();
        $updateCode = $this->discountCodeService->update($id, $request->validated(), $user);
        return ApiResponse::success(['code' => new DiscountCodeResource($updateCode)],'Discount code updated successfully!',200);
    }

    public function destroy(DiscountCode $id)
    {
        $user = Auth::guard('api')->user();
        $this->discountCodeService->delete($id, $user);
        return ApiResponse::success(null, 'Discount code deleted successfully.');
    }
}
