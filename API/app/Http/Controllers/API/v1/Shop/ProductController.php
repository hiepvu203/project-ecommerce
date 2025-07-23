<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Shop;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CreateProductRequest;
use App\Http\Requests\Shop\UpdateProductRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\Upload\CloudinaryService;
use App\Services\Upload\ProductImageService;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(
        protected CloudinaryService $cloud,
        protected ProductImageService $productImageService,
        protected ProductVariantService $productVariantService,
        protected ProductService $productService
    ){}

    public function store(CreateProductRequest $request)
    {
        $user = Auth::user();
        try {
            $product = $this->productService->createProduct( $user, $request->validated(), $request->file('image', []), $request->input('variants', []));

            return ApiResponse::success($product, 'Product created successfully.', 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return ApiResponse::error('Failed to create product.', 500, null);
        }
    }

    public function index(Request $request)
    {
        $products = $this->productService->getActiveProducts($request, $request->input('per_page', 15));
        return ApiResponse::success($products, 'Danh sách sản phẩm', 200, [], ProductResource::class);
    }

    public function show(int $id)
    {
        $product = Product::with(['shop', 'images', 'variants', 'category',])
            ->where('status', 'active')
            ->find($id);
        if (!$product)
            return ApiResponse::fail(null, 'Product not found.', 404);

        return ApiResponse::success(new ProductDetailResource($product), 'Product details');
    }

    public function update(UpdateProductRequest $request, Product $product){
        $user = Auth::user();
        try {
            $updated = $this->productService->update( $user, $product, $request->validated(), $request->file('image', []), $request->input('variants', []));
            return ApiResponse::success($updated, 'Product updated successfully.');
        } catch (\Throwable $e) {
            return ApiResponse::error('Failed to update product.', 500, null);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->productService->deleteProduct(Auth::user(), $product);
            return ApiResponse::success(null, 'Product deleted successfully.');
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }

    public function myProducts(Request $request)
    {
        $products = $this->productService->getMyProducts($request, $request->input('per_page', 15));
        return ApiResponse::success($products, 'Danh sách sản phẩm của bạn', 200, [], ProductResource::class);
    }
}
