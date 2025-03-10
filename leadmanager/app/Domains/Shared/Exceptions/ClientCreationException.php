<?php

namespace App\Domains\Shared\Exceptions;

use Exception;

class ClientCreationException extends Exception
{
    public function __construct(
        string $message = "", 
        int $code = 500, 
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}