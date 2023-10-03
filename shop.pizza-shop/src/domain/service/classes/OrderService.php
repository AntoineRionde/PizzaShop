<?php
namespace pizzashop\shop\domain\service\classes;
use Exception;
use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\entities\catalog\Product;
use pizzashop\shop\domain\entities\order\Item;
use pizzashop\shop\domain\exception\OrderNotFoundException;
use pizzashop\shop\domain\service\interfaces\IOrder;
use pizzashop\shop\domain\entities\order\Order;

class OrderService implements IOrder
{
    private CatalogService $catalogService;

    /**
     * @throws OrderNotFoundException
     */
    public function readOrder(string $id): OrderDTO
    {
        try {
            $commandeEntity = Order::findOrFail($id);

            $itemsEntity = Item::where('commande_id', '=' ,$id)->get();

            $infoProducts = Product::where('numero', '=' ,$itemsEntity->numero)->get();
            $arrayItm = array();
            $i = 0;
            foreach($itemsEntity as $itemEntity) {
                $arrayItm[$i] =  $itemEntity->itemToDTO();
                $arrayItm[$i].array_push($infoProducts->descriptionToDTO());
                $i++;
            }

            $commandeEntity->items = $arrayItm;

            return $commandeEntity->toDTO();
        }catch(Exception $e) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @throws OrderNotFoundException
     */
    public function validateCommande(String $id): OrderDTO
    {
        try {
            $commandeEntity = Order::findOrFail($id);
            $commandeEntity->etatCreation = "VALIDE";
            return $commandeEntity->toDTO();
        }catch(Exception $e) {
            throw new OrderNotFoundException();
        }
    }


    public function createOrder(OrderDTO $orderDTO): void
    {
        try{
            //La méthode interroge le service Catalogue pour obtenir des informations sur chaque produit
            //commandé.
            //La commande est créée : un identifiant est créé, la date de commande est enregistrée, l'état initial
            //de la commande est CREE.
            //Le montant total de la commande est calculé.
            //Un objet de type CommandeDTO est retourné, incluant toutes les informations disponibles.

            $commande = new Commande();
            $commande->id = $orderDTO->id;
            $commande->mail_client = $orderDTO->mail_client;
            $commande->type_livraison = $orderDTO->type_livraison;

            $itemsEntity = Item::where('commande_id', '=' ,$orderDTO->id)->get();

            $arrayItm = array();
            $i = 0;
            foreach($itemsEntity as $itemEntity) {
                $arrayItm[$i] =  $itemEntity->itemToDTOForCreate();

                $i++;
            }













        }catch(Exception $e){
            throw new OrderNotFoundException();
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