<?php

namespace pizzashop\shop\domain\exception;

use Exception;

class OrderRequestInvalidException extends Exception
{
    public function __construct(string $message = "Error : Request is invalid", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}