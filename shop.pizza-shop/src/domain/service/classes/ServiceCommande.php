<?php
namespace domain\service\classes;
use domain\service\interfaces\ICommande;

class ServiceCommande implements ICommande
{
    // fait le constructeur qui passe en parammètre le service de catalogue
    private ServiceCatalogue $serviceCatalogue;

    public function __construct(ServiceCatalogue $serviceCatalogue)
    {
        $this->serviceCatalogue = $serviceCatalogue;
    }
}