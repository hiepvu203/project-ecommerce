<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    protected $statusCode = 500;

    public function __construct(string $message = "", int $statusCode = 500)
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
