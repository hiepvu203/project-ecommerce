<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddToCartRequest;
use App\Http\Requests\Customer\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Helpers\ApiResponse;
use App\Http\Resources\CartItemResource;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/customer/cart",
     *     summary="Lấy thông tin giỏ hàng của người dùng hiện tại",
     *     tags={"Customer - Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cart retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CartResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No product added yet!"
     *     )
     * )
     */
    public function index()
    {
        $user = Auth::user();
        $cart = $this->cartService->getCart($user);

        if (!$cart)
            return ApiResponse::success(null, 'No product added yet!');

        return ApiResponse::success(['cart' => new CartResource($cart)], 'Cart retrieved successfully', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/customer/cart",
     *     summary="Thêm sản phẩm vào giỏ hàng",
     *     tags={"Customer - Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AddToCartRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item added to cart successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CartItemResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/v1/customer/cart/{itemId}",
     *     summary="Cập nhật số lượng sản phẩm trong giỏ hàng",
     *     tags={"Customer - Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="itemId",
     *         in="path",
     *         required=true,
     *         description="ID của cart item",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCartItemRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cart item updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="cart_item", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item removed from cart"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/v1/customer/cart/{itemId}",
     *     summary="Xóa một sản phẩm khỏi giỏ hàng",
     *     tags={"Customer - Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="itemId",
     *         in="path",
     *         required=true,
     *         description="ID của cart item",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item removed from cart successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/v1/customer/cart",
     *     summary="Xóa toàn bộ giỏ hàng",
     *     tags={"Customer - Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cart cleared successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
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
