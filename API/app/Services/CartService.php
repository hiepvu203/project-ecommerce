<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CartRepository;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function __construct(
        protected CartRepository $cartRepository
    ) {}

    public function getCart(User $user, int $perPage = 10)
    {
        return $this->cartRepository->getUserCartWithRelations($user, ['items.product', 'items.variant'], $perPage);
    }

    public function addToCart(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            /** @var Cart $cart */
            $cart = $user->cart;
            if (!$cart) {
                $cart = $this->cartRepository->createCart($user);
            }

            $product = Product::findOrFail($data['product_id']);

            if ($user->type === User::TYPE_SHOP_OWNER && $user->shop && $product->shop_id === $user->shop->id) {
                throw new \Exception('Shop owner cannot buy products from their own shop', 403);
            }

            $variant = null;
            $stock = $product->quantity;
            $price = $product->price;
            if (!empty($data['variant_id'])) {
                $variant = ProductVariant::where('id', $data['variant_id'])
                    ->where('product_id', $product->id)
                    ->firstOrFail();
                $stock = $variant->quantity;
                $price = (float) ($variant->price ?? $product->price);
            }

            $existingItem = $this->cartRepository->findCartItemByProductAndVariant($cart, $data['product_id'], $data['variant_id'] ?? null);

            $newQuantity = $data['quantity'];
            if ($existingItem) {
                $newQuantity += $existingItem->quantity;
            }
            if ($newQuantity > $stock) {
                throw new \Exception('Số lượng vượt quá tồn kho.', 400);
            }

            if ($existingItem) {
                $this->cartRepository->updateCartItem($existingItem, [
                    'quantity' => $newQuantity,
                ]);
                $cartItem = $existingItem;
            } else {
                $cartItem = $this->cartRepository->createCartItem($cart, [
                    'product_id' => $data['product_id'],
                    'variant_id' => $data['variant_id'] ?? null,
                    'quantity' => $data['quantity'],
                    'price_at_added' => $price,
                ]);
            }

            return $cartItem->load(['product', 'variant']);
        });
    }

    public function updateQuantity(User $user, int $itemId, int $quantity)
    {
        /** @var Cart $cart */
        $cart = $user->cart;
        if (!$cart) {
            throw new \Exception('Cart not found', 404);
        }

        $cartItem = $this->cartRepository->findCartItem($cart, $itemId);

        if ($quantity <= 0) {
            $this->cartRepository->deleteCartItem($cartItem);
            return null;
        }

        $this->cartRepository->updateCartItem($cartItem, ['quantity' => $quantity]);
        return $cartItem->load(['product', 'variant']);
    }

    public function removeItem(User $user, int $itemId)
    {
        /** @var Cart $cart */
        $cart = $user->cart;
        if (!$cart) {
            throw new \Exception('Cart not found', 404);
        }

        $cartItem = $this->cartRepository->findCartItem($cart, $itemId);
        $this->cartRepository->deleteCartItem($cartItem);
    }

    public function clearCart(User $user)
    {
        /** @var Cart $cart */
        $cart = $user->cart;
        if (!$cart) {
            throw new \Exception('Cart not found', 404);
        }
        $this->cartRepository->clearCart($cart);
    }
}
