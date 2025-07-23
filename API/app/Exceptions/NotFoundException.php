<?php

declare(strict_types=1);

namespace App\Exceptions;

class NotFoundException extends BaseException
{
    public function __construct(string $message = "Resource not found", int $statusCode = 404)
    {
        parent::__construct($message, $statusCode);
    }
}
