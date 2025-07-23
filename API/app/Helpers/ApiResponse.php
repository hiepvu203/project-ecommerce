<?php

declare(strict_types= 1);

namespace App\Helpers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'Thành công!', string|int $code = 200, array $meta = [], ?string $resourceClass = null): JsonResponse
    {
        $response = [
            'status' => 'success',
            'code' => $code,
            'message' => $message,
        ];

        if ($data instanceof LengthAwarePaginator) {
            $eloquentCollection = new Collection($data->items());

            $items = $resourceClass ? $resourceClass::collection($eloquentCollection) : $eloquentCollection;

            $response['data'] = $items instanceof AnonymousResourceCollection ? $items->resolve() : $items->toArray();
            
            $response['meta'] = array_merge($meta, [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]);
        }
        elseif (is_array($data) && !empty($data)) {
            $paginatorKey = array_key_first($data);
            if ($data[$paginatorKey] instanceof LengthAwarePaginator) {
                $response['data'] = [$paginatorKey => $data[$paginatorKey]->items()];
                $response['meta'] = array_merge($meta, [
                    'total' => $data[$paginatorKey]->total(),
                    'per_page' => $data[$paginatorKey]->perPage(),
                    'current_page' => $data[$paginatorKey]->currentPage(),
                    'last_page' => $data[$paginatorKey]->lastPage(),
                    'from' => $data[$paginatorKey]->firstItem(),
                    'to' => $data[$paginatorKey]->lastItem(),
                ]);
            } else {
                $response['data'] = $data;
                if (!empty($meta)) $response['meta'] = $meta;
            }
        }
        else {
            $response['data'] = $data;
            if (!empty($meta)) $response['meta'] = $meta;
        }

        return response()->json($response, is_numeric($code) ? (int)$code : 200);
    }

    public static function fail(mixed $data = null, string $message = 'Dữ liệu không phù hợp!', string|int $code = 400): JsonResponse {
        return response()->json([
            'status'  => 'fail',
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ], is_numeric($code) ? (int)$code : 400);
    }

    public static function error(string $message = 'Lỗi hệ thống!', string|int $code = 500, mixed $data = null): JsonResponse {
        $response = [
            'status'  => 'error',
            'code'    => $code,
            'message' => $message,
        ];

        if (!is_null($data)) $response['data'] = $data;

        return response()->json($response, is_numeric($code) ? (int)$code : 500);
    }
}
