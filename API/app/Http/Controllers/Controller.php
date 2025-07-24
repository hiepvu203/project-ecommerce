<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Project Ecommerce API",
 *     description="Tài liệu API cho hệ thống Ecommerce"
 * )
 *
 * @OA\Server(
 *     url="http://localhost",
 *     description="Local server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    use AuthorizesRequests;
}
