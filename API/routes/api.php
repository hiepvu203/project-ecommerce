<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Customer\AuthController;
use App\Http\Controllers\API\v1\Admin\SaleController;
use App\Http\Controllers\Api\v1\Admin\StaffController;
use App\Http\Controllers\Api\v1\Admin\DiscountCodeController;
use App\Http\Controllers\Api\v1\Admin\PermissionController;
use App\Http\Controllers\Api\v1\Admin\PermissionRoleController;
use App\Http\Controllers\API\v1\Admin\RoleController;
use App\Http\Controllers\API\v1\Admin\ShopAdminController;
use App\Http\Controllers\API\v1\Admin\VerifyProductController;
use App\Http\Controllers\API\v1\Admin\SystemCategoryController;
use App\Http\Controllers\API\v1\Admin\ShopVerificationAdminController;
use App\Http\Controllers\Api\v1\Admin\UserRoleController;
use App\Http\Controllers\Api\v1\Customer\CartController;
use App\Http\Controllers\Api\v1\Customer\OrderController;
use App\Http\Controllers\API\v1\Shop\ShopController;
use App\Http\Controllers\API\v1\Shop\ProductController;
use App\Http\Controllers\API\v1\Shop\ShopOrderController;
use App\Http\Controllers\API\v1\Customer\SubOrderController;
use App\Http\Controllers\Api\v1\Customer\UserProfileController;
use App\Http\Controllers\API\v1\Shop\ShopVerificationController;

//      --- Public routes ---
Route::prefix('v1')->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('shops/{id}', [ShopController::class, 'showPublic'])->name('shops.show');
    Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::post('password/forgot', [AuthController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
});

//      --- Authentication ---
Route::prefix('v1/auth')->group(function () {
    Route::post('signup', [AuthController::class, 'register'])->name('auth.signup');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('auth.logout');
    Route::post('tokens/refresh', [AuthController::class, 'refresh'])->name('auth.tokens.refresh');
});

Route::get('v1/email-verifications/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification())))
        return ApiResponse::fail(null, 'Verification link is invalid!', 403);

    if ($user->hasVerifiedEmail())
        return ApiResponse::success(null, 'Email has already been verified.', 200);

    $user->markEmailAsVerified();
    event(new Verified($user));
    return ApiResponse::success(null, 'Email verification successful!');
})->middleware(['signed'])->name('verification.verify');

Route::post('v1/email-verifications/resend', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $user = User::where('email', $request->input('email'))->first();

    if (!$user)
        return ApiResponse::fail(null, 'Không tìm thấy người dùng!', 404);

    if ($user->hasVerifiedEmail())
        return ApiResponse::fail(null, 'Email đã được xác thực!', 422);

    $user->sendEmailVerificationNotification();

    return ApiResponse::success(null, 'Email xác thực đã được gửi lại!');
})->middleware('throttle:6,1')->name('verification.resend');

//      ---- Profile ----
Route::prefix('v1/profiles')->middleware('auth:api')->group(function () {
    Route::get('/', [UserProfileController::class, 'getProfile'])->name('profiles.index');
    Route::put('/', [UserProfileController::class, 'update'])->name('profiles.update');
    Route::post('avatars', [UserProfileController::class, 'uploadAvatar'])->name('profiles.avatars');
    Route::post('passwords/change', [UserProfileController::class, 'changePassword'])->name('profiles.passwords.change');
});

//      ---- Customer ----
Route::prefix('v1/customers')->middleware(['auth:api', 'user_type:customer'])->group(function () {
    Route::prefix('carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'addToCart']);
        Route::put('items/{itemId}', [CartController::class, 'updateQuantity']);
        Route::delete('/items/{itemId}', [CartController::class, 'removeItem']);
        Route::delete('/', [CartController::class, 'clearCart']);
    });

    Route::post('orders/previews', [OrderController::class, 'previewCheckout']);
    Route::post('orders', [OrderController::class, 'checkout']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::get('purchased-products', [SubOrderController::class, 'purchasedProducts']);
    Route::post('sub-orders/{id}/cancellations', [SubOrderController::class, 'cancel']);
});

//   ---- shop ----
Route::prefix('v1/shops')->middleware(['auth:api'])->group(function () {
    Route::post('registrations', [ShopController::class, 'register'])->name('shops.registrations');
    Route::post('verifications/documents', [ShopVerificationController::class, 'submit'])->name('shops.verifications.documents');
});

Route::prefix('v1/my-shop')->middleware(['auth:api', 'user_type:shop_owner'])->group(function () {
    Route::get('/', [ShopController::class, 'getMyShop'])->name('my-shop.index');
    Route::post('/', [ShopController::class, 'update'])->name('my-shop.update');
    Route::get('products', [ProductController::class, 'myProducts'])->name('my-shop.products.index');
    Route::post('products', [ProductController::class, 'store'])->name('my-shop.products.create');
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    Route::get('orders', [ShopOrderController::class, 'index']);
    Route::get('orders/{id}', [ShopOrderController::class, 'show']);
    Route::post('orders/{id}/approvals', [ShopOrderController::class,'approve']);

    Route::post('sales', [SaleController::class, 'store']);
    Route::post('locks', [ShopController::class, 'lock']);
});

Route::prefix('v1/admin')->middleware(['auth:api', 'user_type:admin'])->group(function () {
    Route::get('staffs', [StaffController::class, 'index']);
    Route::post('staffs', [StaffController::class, 'store']);
    Route::get('shops/verifications', [ShopVerificationAdminController::class, 'index']);
    Route::post('shops/{shopId}/approvals', [ShopVerificationAdminController::class, 'approve']);
    Route::post('shops/{shopId}/rejections', [ShopVerificationAdminController::class, 'reject']);
    Route::post('products/{product}/approvals', [VerifyProductController::class, 'approve']);
    Route::post('products/{product}/rejections', [VerifyProductController::class, 'reject']);
    Route::post('shops/{shop}/unlocks', [ShopAdminController::class, 'unlock']);
    Route::post('system-categories', [SystemCategoryController::class, 'store']);
    Route::get('system-categories', [SystemCategoryController::class, 'index']);
    Route::get('system-categories/{id}', [SystemCategoryController::class, 'show']);
    Route::put('system-categories/{id}', [SystemCategoryController::class, 'update']);

    Route::get('discount-codes', [DiscountCodeController::class, 'index']);
    Route::post('discount-codes', [DiscountCodeController::class, 'store']);
    Route::put('discount-codes/{id}', [DiscountCodeController::class,'update']);
    Route::delete('discount-codes/{id}', [DiscountCodeController::class,'destroy']);

    Route::post('sales', [SaleController::class, 'store']);

    //      ---- Decentralization ----
    Route::prefix('decentralization')->group(function () {
        // --- Role ---
        Route::get('roles', [RoleController::class, 'index']);
        Route::post('roles', [RoleController::class, 'store']);
        Route::put('roles/{role}', [RoleController::class, 'update']);
        Route::delete('roles/{role}', [RoleController::class, 'destroy']);

        // --- user role
        Route::get('user-roles', [UserRoleController::class, 'index']);
        Route::post('user-roles', [UserRoleController::class,'store']);
        Route::put('user-role/{id}', [UserRoleController::class,'update']);
        Route::delete('user-role/{id}', [UserRoleController::class,'destroy']);

        // --- permission ---
        Route::get('permissions', [PermissionController::class, 'index']);
        Route::post('permissions', [PermissionController::class,'store']);
        Route::put('permissions/{id}', [PermissionController::class,'update']);
        Route::delete('permissions/{id}', [PermissionController::class,'destroy']);

        // --- permission role ---
        Route::get('permission-roles', [PermissionRoleController::class, 'index']);
        Route::post('permission-roles', [PermissionRoleController::class,'store']);
        Route::put('permission-roles/{id}', [PermissionRoleController::class,'update']);
        Route::delete('permission-roles/{id}', [PermissionRoleController::class,'destroy']);
    });
});

Route::middleware(['auth:api', 'user_type:admin,shop_owner'])->prefix('v1/promotions')->group(function () {
    Route::prefix('discount-codes')->group(function () {
        Route::get('', [DiscountCodeController::class, 'index']);
        Route::post('', [DiscountCodeController::class, 'store']);
        Route::put('update/{id}', [DiscountCodeController::class,'update']);
        Route::delete('delete/{id}', [DiscountCodeController::class,'destroy']);
    });
});
