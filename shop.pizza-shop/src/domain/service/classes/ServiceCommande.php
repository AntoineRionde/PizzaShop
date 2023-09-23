<?php
namespace domain\service\classes;
use domain\service\interfaces\ICommander;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\exception\ServiceCommandeNotFoundException;
use pizzashop\shop\domain\dto\commande\CommandeDTO;

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
            $commande = Commande::find($id);
            return $commande->toDTO();            
        }catch(\Exception $e){
            throw new ServiceCommandeNotFoundException($e->getMessage());
        }
    }

    /**
     */
    public function validateCommande(String $id): CommandeDTO
    {
        $commandeDTO = new CommandeDTO();

        return $commandeDTO;
    }
}