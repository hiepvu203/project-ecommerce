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

/**
 * @OA\Tag(
 *     name="Shop / Products",
 *     description="Product management for authenticated shop owners"
 * )
 */
class ProductController extends Controller
{
    public function __construct(
        protected CloudinaryService $cloud,
        protected ProductImageService $productImageService,
        protected ProductVariantService $productVariantService,
        protected ProductService $productService
    ){}

    /**
     * @OA\Post(
     *     path="/api/v1/my-shop/products",
     *     operationId="shopCreateProduct",
     *     tags={"Shop / Products"},
     *     summary="Create a new product",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/CreateProductRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product created successfully.")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     operationId="publicProductIndex",
     *     tags={"Public"},
     *     summary="Public paginated product list",
     *     @OA\Parameter(name="category_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=12)),
     *     @OA\Response(
     *         response=200,
     *         description="Product list",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $products = $this->productService->getActiveProducts($request, $request->input('per_page', 15));
        return ApiResponse::success($products, 'Danh sách sản phẩm', 200, [], ProductResource::class);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     operationId="publicProductShow",
     *     tags={"Public"},
     *     summary="Public product details",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/ProductDetailResource")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function show(int $id)
    {
        $product = Product::with(['shop', 'images', 'variants', 'category',])
            ->where('status', 'active')
            ->find($id);
        if (!$product)
            return ApiResponse::fail(null, 'Product not found.', 404);

        return ApiResponse::success(new ProductDetailResource($product), 'Product details');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/my-shop/products/{product}",
     *     operationId="shopUpdateProduct",
     *     tags={"Shop / Products"},
     *     summary="Update an existing product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/UpdateProductRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product updated successfully.")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function update(UpdateProductRequest $request, Product $product){
        $user = Auth::user();
        try {
            $updated = $this->productService->update( $user, $product, $request->validated(), $request->file('image', []), $request->input('variants', []));
            return ApiResponse::success($updated, 'Product updated successfully.');
        } catch (\Throwable $e) {
            return ApiResponse::error('Failed to update product.', 500, null);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/my-shop/products/{product}",
     *     operationId="shopDeleteProduct",
     *     tags={"Shop / Products"},
     *     summary="Delete a product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function destroy(Product $product)
    {
        try {
            $this->productService->deleteProduct(Auth::user(), $product);
            return ApiResponse::success(null, 'Product deleted successfully.');
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/my-shop/products",
     *     operationId="shopMyProducts",
     *     tags={"Shop / Products"},
     *     summary="List products owned by the current shop",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="category_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=10)),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated product list",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function myProducts(Request $request)
    {
        $products = $this->productService->getMyProducts($request, $request->input('per_page', 15));
        return ApiResponse::success($products, 'Danh sách sản phẩm của bạn', 200, [], ProductResource::class);
    }
}
