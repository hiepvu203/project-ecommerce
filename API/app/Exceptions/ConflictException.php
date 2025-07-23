<?php

declare(strict_types=1);

namespace App\Exceptions;

class ConflictException extends BaseException
{
    public function __construct(string $message = "Conflict", int $statusCode = 409)
    {
        parent::__construct($message, $statusCode);
    }
}
