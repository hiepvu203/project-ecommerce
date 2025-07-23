<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Admin;

use App\Enums\StatusEnum;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifyRequest;
use App\Http\Resources\ProductDetailResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyProductController extends Controller
{
    public function verify(int $productId, VerifyRequest $request)
    {
        $product = Product::where('id', $productId)
            ->where('status', StatusEnum::DRAFT->value)
            ->first();

        if (!$product) {
            return ApiResponse::fail(null, 'Product does not exist or has been verified.', 400);
        }

        DB::beginTransaction();

        try {
            if ($request->status === StatusEnum::APPROVED->value) {
                $product->update([
                    'status' => StatusEnum::ACTIVE->value,
                    'rejection_reason' => null,
                ]);
            } else {
                $product->update([
                    'status' => StatusEnum::DRAFT->value,
                    'rejection_reason' => $request->rejection_reason,
                ]);
            }

            DB::commit();
            return ApiResponse::success(['product' => new ProductDetailResource($product)], 'Product verification successful.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return ApiResponse::error('An error occurred while verifying the product.', 500);
        }
    }
}
