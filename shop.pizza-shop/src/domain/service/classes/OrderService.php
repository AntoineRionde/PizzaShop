<?php
namespace pizzashop\shop\domain\service\classes;
use Exception;
use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\exception\OrderServiceNotFoundException;
use pizzashop\shop\domain\service\interfaces\IOrder;
use pizzashop\shop\domain\entities\commande\Commande;

class OrderService implements IOrder
{
    private CatalogService $catalogService;

    /**
     * @throws OrderServiceNotFoundException
     */
    public function readOrder(String $id): OrderDTO
    {
        try {
            $commandeEntity = Commande::findOrFail($id);
            return $commandeEntity->toDTO();
        }catch(Exception $e) {
            throw new OrderServiceNotFoundException();
        }
    }

    /**
     * @throws OrderServiceNotFoundException
     */
    public function validateCommande(String $id): OrderDTO
    {
        try {
            $commandeEntity = Commande::findOrFail($id);
            $commandeEntity->etatCreation = "VALIDE";
            return $commandeEntity->toDTO();
        }catch(Exception $e) {
            throw new OrderServiceNotFoundException();
        }
    }


    public function createOrder(OrderDTO $orderDTO): void
    {
        try{

            // liste des items commandés (pour chacun, numéro, taille, quantité).
            //La méthode interroge le service Catalogue pour obtenir des informations sur chaque produit
            //commandé.
            //La commande est créée : un identifiant est créé, la date de commande est enregistrée,
            //Le montant total de la commande est calculé.
            //Un objet de type CommandeDTO est retourné, incluant toutes les informations disponibles





        }catch(Exception $e){
            throw new OrderServiceNotFoundException();
        }
    }

    public function validateOrder(string $id): void
    {
        // TODO: Implement validateOrder() method.
    }

    public function getOrder(string $id): OrderDTO
    {
        // TODO: Implement getOrder() method.
    }
}