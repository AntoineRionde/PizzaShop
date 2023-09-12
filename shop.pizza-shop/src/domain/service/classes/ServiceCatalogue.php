<?php

namespace domain\service\classes;

use domain\service\interfaces\ICatalogue;

class ServiceCatalogue implements ICatalogue
{
  // fait un constructeur qui passe en paramètre le service de commande
    private ServiceCommande $serviceCommande;

    public function __construct(ServiceCommande $serviceCommande)
    {
        $this->serviceCommande = $serviceCommande;
    }
}