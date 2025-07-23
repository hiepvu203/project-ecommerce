<?php

use App\Exceptions\BusinessException;
use App\Exceptions\ForbiddenException;
use App\Helpers\ApiResponse;
use App\Http\Middleware\CheckUserType;
use App\Http\Middleware\SendVerificationEmail;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'user_type' => CheckUserType::class,
        ]);
    })
    ->withEvents([

    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request = null) {
            if ( $e instanceof AccessDeniedHttpException ){
                if ( $e->getPrevious() instanceof AuthorizationException ){
                    return ApiResponse::fail(null, 'Bạn không có quyền thực hiện hành động này.', 403);
                }
            }

            // if ($e instanceof NotFoundHttpException) {
            //     if ($request) {
            //         return ApiResponse::fail(null, 'Không tìm thấy đường dẫn!.', 404);
            //     }
            // }

            if ($e instanceof ForbiddenException) {
                if ($request) {
                    return ApiResponse::fail(null, 'Bạn không có quyền thực hiện hành động này!.', 403);
                }
            }
            // Bạn chỉ có thể cập nhật ngày kết thúc hoặc tăng giới hạn sử dụng sau khi mã đã bắt đầu.

            if ($e instanceof AuthenticationException) {
                if ($request) {
                    return ApiResponse::fail(null, 'Bạn chưa đăng nhập hoặc token không hợp lệ!', 401);
                }
            }

            if ($e instanceof BusinessException) {
                if ($request) {
                    return ApiResponse::fail(null, 'Bạn chỉ có thể cập nhật ngày kết thúc hoặc tăng giới hạn sử dụng sau khi mã đã bắt đầu.!', 401);
                }
            }
        });
    })->create();
