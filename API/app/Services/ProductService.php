<?php

declare(strict_types= 1);

namespace App\Services;

use App\Enums\StatusEnum;
use App\Exceptions\BusinessException;
use App\Exceptions\UnauthorizedException;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Services\Upload\ProductImageService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService{

    public function __construct(
        protected ProductRepository $productRepo,
        protected DatabaseManager $db,
        protected ProductImageService $productImageService,
        protected ProductVariantService $productVariantService,
    ) {}

    public function createProduct(User $user, array $data, array $images = [], array $variants = []): Product
    {
        $shop = $user->shop;
        if (!$shop) {
            throw new BusinessException('Shop not found');
        }

        return DB::transaction(function () use ($shop, $data, $images, $variants) {
            $slug = $data['slug'] ?? Str::slug($data['name']);
            $originalSlug = $slug;
            $counter = 1;

            while (
                Product::query()
                    ->where('shop_id', $shop->id)
                    ->where('slug', $slug)
                    ->exists()
            ) { $slug = $originalSlug . '-' . $counter++; }

            $product = $this->productRepo->create([
                ...$data,
                'shop_id' => $shop->id,
                'slug'    => $slug,
                'status'  => StatusEnum::DRAFT->value,
            ]);

            // Upload images
            $imageData = [];

            foreach ($images as $index => $img) {
                $url = $this->productImageService->upload($product->id, $img);
                $imageData[] = [
                    'product_id'     => $product->id,
                    'image'          => $url,
                    'order_position' => $index,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }

            ProductImage::insert($imageData);

            if (!empty($variants)) {
                $this->productVariantService->createVariants($product, $variants);
            }

            return $product->load(['images', 'variants']);
        });
    }


    public function update(User $user, Product $product, array $data, array $images = [], array $variants = []): Product
    {
        if ($product->shop_id !== $user->shop?->id) {
            throw new UnauthorizedException('Unauthorized to update product');
        }

        return DB::transaction(function () use ($product, $data, $images, $variants) {
            if (isset($data['name']) && empty($data['slug'])) {
                $slug = Str::slug($data['name']);

                $originalSlug = $slug;
                $counter = 1;

                while (
                    Product::query()
                        ->where('shop_id', $product->shop_id)
                        ->where('id', '!=', $product->id) // Loại trừ chính nó
                        ->where('slug', $slug)
                        ->exists()
                ) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                $data['slug'] = $slug;
            }
            $this->productRepo->update($product, $data);

            if(!empty($images)){
                $product->images()->delete();

                $imageData = [];

                foreach ($images as $index => $img) {
                    if (!$img->isValid()) continue;

                    $url = $this->productImageService->upload($product->id, $img);

                    $imageData[] = [
                        'product_id'     => $product->id,
                        'image'          => $url,
                        'order_position' => $index,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ];
                }

                if (!empty($imageData)) {
                    ProductImage::insert($imageData);
                }
            }

            if (!empty($variants)) {
                $product->variants()->delete();
                $this->productVariantService->createVariants($product, $variants);
            }

            return $product->load(['images', 'variants']);
        });
    }

    public function deleteProduct(User $user, Product $product): bool
    {
        if ($product->shop_id !== $user->shop?->id || $product->trashed()) {
            throw new UnauthorizedException('Unauthorized to update product');
        }

        return $this->productRepo->delete($product);
    }

    public function getActiveProducts(Request $request, int $perPage = 15)
    {
        $filters = [
            'category_id' => $request->input('category_id'),
            'search' => $request->input('search'),
        ];
        return $this->productRepo->getActiveProducts($filters, $perPage);
    }

    public function getMyProducts(Request $request, int $perPage = 15)
    {
        $user = auth('api')->user();
        $shop = $user->shop;

        if (!$shop) {
            throw new \Exception('Shop not found.', 404);
        }

        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'category_id' => $request->input('category_id'),
        ];
        return $this->productRepo->getShopProducts($shop, $filters, $perPage);
    }
}
