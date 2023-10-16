<?php
namespace pizzashop\shop\domain\service\classes;
use Exception;
use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\entities\order\Item;
use pizzashop\shop\domain\exception\OrderNotFoundException;
use pizzashop\shop\domain\exception\OrderRequestInvalidException;
use pizzashop\shop\domain\service\interfaces\IOrder;
use pizzashop\shop\domain\entities\order\Order;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class OrderService implements IOrder
{
    private CatalogService $catalogService;
    private LoggerInterface $logger;

    public function __construct(CatalogService $catalogService, LoggerInterface $logger)
    {
        $this->catalogService = $catalogService;
        $this->logger = $logger;
    }

    /**
     * @throws OrderNotFoundException
     */
    public function createOrder(OrderDTO $orderDTO): OrderDTO
    {
        try{
            $commandeEntity = new Order();
            $commandeId = Uuid::uuid4()->toString();
            $commandeEntity->id = $commandeId;
            $commandeEntity->date_commande = date('Y-m-d H:i:s');
            $commandeEntity->type_livraison = $orderDTO->type_livraison;
            $commandeEntity->etat = Order::ETAT_CREE;

            $commandeEntity->mail_client = $orderDTO->mail_client;

            $montantTotal = 0;
            foreach($orderDTO->items as $item) {
                $product = $this->catalogService->readProduct($item->numero);

                $itemEntity = new Item();
                $itemEntity->id = $product->id;
                $itemEntity->numero = $item->numero;
                $itemEntity->libelle = $product->libelle;
                $itemEntity->taille = $item->taille;
                $itemEntity->libelle_taille = $item->taille == 1 ? 'normale' : 'grande';
                $itemEntity->quantite = $item->quantite;
                $itemEntity->commande_id = $commandeId;
                $this->logger->info('Item créé', $itemEntity->toDTO()->toArray());
                //$itemEntity->save(); Pas sûr

                $montantTotal += $product->prix * $item->quantite;
            }

            $commandeEntity->montant_total = $montantTotal;
            //$commandeEntity->save(); Pas sûr
            $commandeDTO = $commandeEntity->toDTO();
            $this->logger->info('Commande créée', $commandeDTO->toArray());
            return $commandeDTO;

        }catch(Exception $e){
            throw new OrderNotFoundException();
        }
    }

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
                $arrayItm[$i] =  $itemEntity->toDTO();
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


}