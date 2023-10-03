<?php

namespace pizzashop\shop\domain\exception;

use Exception;

class InternalServerException extends Exception
{
    public function __construct(string $message = "Error : Internal Server Error", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}