<?php

namespace domain\service\classes;

use domain\service\interfaces\ICatalogue;

class ServiceCatalogue implements ICatalogue
{
    private ServiceCommande $serviceCommande;

    public function __construct(ServiceCommande $serviceCommande)
    {
        $this->serviceCommande = $serviceCommande;
    }
}