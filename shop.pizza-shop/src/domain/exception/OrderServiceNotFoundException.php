<?php
namespace pizzashop\shop\domain\exception;

use Exception;
use Throwable;

class OrderServiceNotFoundException extends Exception{
    public function __construct(string $message = "Erreur : la commande n'existe pas", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}