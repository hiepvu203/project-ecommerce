<?php

declare(strict_types=1);

namespace App\Exceptions;

class BusinessException extends BaseException
{
    public function __construct(string $message = "Business logic error", int $statusCode = 400)
    {
        parent::__construct($message, $statusCode);
    }
}
