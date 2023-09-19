<?php
namespace domain\exception;

use Exception;

class ServiceCommandeNotFoundException extends Exception{
    public function __construct(string $message = "Erreur : la commande n'existe pas", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}