<?php
namespace pizzashop\shop\domain\service\classes;
use Exception;
use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\entities\order\Item;
use pizzashop\shop\domain\exception\OrderNotFoundException;
use pizzashop\shop\domain\exception\OrderRequestInvalidException;
use pizzashop\shop\domain\service\interfaces\IOrder;
use pizzashop\shop\domain\entities\order\Order;
use Ramsey\Uuid\Uuid;

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
            $arrayItm = array();
            $i = 0;
            foreach($itemsEntity as $itemEntity) {
                $arrayItm[$i] =  $itemEntity->itemToDTO();
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
    public function validateOrder(string $id): OrderDTO
    {
        try {
            $commandeEntity = Order::findOrFail($id);
            if($commandeEntity->etatCreation !== Order::ETAT_CREE) {
                throw new OrderRequestInvalidException();
            }
            $commandeEntity->etatCreation = Order::ETAT_VALIDE;
            return $commandeEntity->toDTO();
        }catch(Exception $e) {
            throw new OrderNotFoundException();
        }
    }


    public function createOrder(OrderDTO $orderDTO): void
    {
        try{
            $commandeEntity = new Order();
            $commandeId = Uuid::uuid4()->toString();
            $commandeEntity->id = $commandeId;
            $commandeEntity->date_commande = $orderDTO->date_commande;
            $commandeEntity->type_livraison = $orderDTO->type_livraison;
            $commandeEntity->etat = Order::ETAT_CREE;
            $commandeEntity->montant_total = $orderDTO->montant_total;
            $commandeEntity->mail_client = $orderDTO->mail_client;
            $commandeEntity->save();

            $items = $orderDTO->items;
            foreach($items as $item) {
                $product = $this->catalogService->readProduct($item->numero);

                $itemEntity = new Item();
                $itemEntity->id = $product->id;
                $itemEntity->numero = $item->numero;
                $itemEntity->libelle = $product->libelle;
                $itemEntity->taille = $item->taille;
                $itemEntity->libelle_taille = $item->taille == 1 ? 'normale' : 'grande';
                $itemEntity->quantite = $item->quantite;
                $itemEntity->commande_id = $commandeId;
                $itemEntity->save();
            }

        }catch(Exception $e){
            throw new OrderNotFoundException();
        }
    }
}