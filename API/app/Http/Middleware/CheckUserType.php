<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = Auth::guard('api')->user();

        if (!$user || !in_array($user->type, $types, strict: true))  // so sánh kiểu strict để đảm bảo đúng kiểu dữ liệu
            return ApiResponse::fail(null, "You don't authorization in resource.", 403);

        return $next($request);
    }
}
