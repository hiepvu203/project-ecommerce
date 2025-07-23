<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;

class CartRepository
{
    public function getUserCartWithRelations($user, array $relations = ['items.product', 'items.variant'], int $perPage = 10)
    {
        return $user->cart()->with($relations)->first();
    }

    public function findCartItem(Cart $cart, int $itemId): ?CartItem
    {
        return $cart->items()->findOrFail($itemId);
    }

    public function findCartItemByProductAndVariant(Cart $cart, int $productId, $variantId = null): ?CartItem
    {
        return $cart->items()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();
    }

    public function createCart(User $user): Cart
    {
        return Cart::create(['user_id' => $user->id]);
    }

    public function createCartItem(Cart $cart, array $data): CartItem
    {
        return $cart->items()->create($data);
    }

    public function updateCartItem(CartItem $item, array $data): bool
    {
        return $item->update($data);
    }

    public function deleteCartItem(CartItem $item): ?bool
    {
        return $item->delete();
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }
}
