<?php
namespace domain\service\classes;
use domain\service\interfaces\ICommande;
use domain\exception\ServiceCommandeNotFoundException;

class ServiceCommande implements ICommande
{
    private ServiceCatalogue $serviceCatalogue;

    public function __construct(ServiceCatalogue $serviceCatalogue)
    {
        $this->serviceCatalogue = $serviceCatalogue;
    }

    public function readCommande(String $UUID): CommandeDTO
    {
        if($UUID) {
            return new CommandeDTO();
        }else{
            throw new ServiceCommandeNotFoundException();
        }
    }

    public function validateCommande(String $UUID): CommandeDTO
    {
        if($UUID) {
            return new CommandeDTO();
        }else{
            throw new ServiceCommandeNotFoundException();
        }
    }


}