<?php
namespace pizzashop\shop\domain\exception;

use Exception;
use Throwable;

class OrderNotFoundException extends Exception{
    public function __construct(string $message = "Error : Order doesn't exist", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}