<?php

namespace pizzashop\auth\api\domain\exceptions;

use Exception;
use Throwable;

class CredentialsException extends Exception
{
    public function __construct($message = "Invalid credentials", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}