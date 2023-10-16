<?php

namespace pizzashop\shop\domain\exception;

use Exception;

class CreationFailedException extends Exception
{

    public function __construct()
    {
        parent::__construct("The creation of the order failed.");
    }
}