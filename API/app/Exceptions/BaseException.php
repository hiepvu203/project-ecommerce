<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseException extends HttpException
{
    protected $statusCode = 500;

    public function __construct(string $message = "", int $statusCode = 500)
    {
        parent::__construct($statusCode, $message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function render()
    {
        return ApiResponse::fail(null, $this->getMessage(), $this->getStatusCode());
    }
}
