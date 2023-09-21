<?php
namespace domain\service\classes;
use domain\dto\CommandeDTO;
use domain\service\interfaces\ICommander;
use domain\entities\commande\commande;
use domain\exception\ServiceCommandeNotFoundException;

class ServiceCommande implements ICommander
{
    private ServiceCatalogue $serviceCatalogue;

    public function __construct(ServiceCatalogue $serviceCatalogue)
    {
        $this->serviceCatalogue = $serviceCatalogue;
    }

    /**
     * @throws ServiceCommandeNotFoundException
     */
    public function readCommande(String $id): CommandeDTO
    {
        try{
            $commande = commande::find($id);
            return $commande->toDTO();            
        }catch(\Exception $e){
            throw new ServiceCommandeNotFoundException($e->getMessage());
        }


        return $commandeDTO;
    }

    /**
     * @throws ServiceCommandeNotFoundExceptioncs
     */
    public function validateCommande(String $id): CommandeDTO
    {
        $commandeDTO = new CommandeDTO();

        return $commandeDTO;
    }
}