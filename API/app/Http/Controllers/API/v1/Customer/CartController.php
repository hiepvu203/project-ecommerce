<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddToCartRequest;
use App\Http\Requests\Customer\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Helpers\ApiResponse;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\Short\CartItemShortResource;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $cart = $this->cartService->getCart($user);

        if (!$cart)
            return ApiResponse::success(null, 'No product added yet!');

        return ApiResponse::success(['cart' => new CartResource($cart)], 'Cart retrieved successfully', 200);
    }

    public function addToCart(AddToCartRequest $request)
    {
        try {
            $user = Auth::user();
            $cartItem = $this->cartService->addToCart($user, $request->validated());
            return ApiResponse::success(['cart_item' => new CartItemResource($cartItem)], 'Item added to cart successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }

    public function updateQuantity(UpdateCartItemRequest $request, int $itemId)
    {
        try {
            $user = Auth::user();
            $cartItem = $this->cartService->updateQuantity($user, $itemId, $request->quantity);

            if (!$cartItem) {
                return ApiResponse::success(null, 'Item removed from cart');
            }

            return ApiResponse::success([
                'cart_item' => $cartItem
            ], 'Cart item updated successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }

    public function removeItem(int $itemId): JsonResponse
    {
        try {
            $user = Auth::user();
            $this->cartService->removeItem($user, $itemId);

            return ApiResponse::success(null, 'Item removed from cart successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }

    public function clearCart(): JsonResponse
    {
        try {
            $user = Auth::user();
            $this->cartService->clearCart($user);

            return ApiResponse::success(null, 'Cart cleared successfully');
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 500;
            return ApiResponse::error($e->getMessage(), 500, null);
        }
    }
}
