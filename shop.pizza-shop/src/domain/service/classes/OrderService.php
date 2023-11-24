<?php

namespace pizzashop\shop\domain\service\classes;

use Exception;
use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\entities\order\Item;
use pizzashop\shop\domain\entities\order\Order;
use pizzashop\shop\domain\exception\CreationFailedException;
use pizzashop\shop\domain\exception\OrderNotFoundException;
use pizzashop\shop\domain\exception\OrderRequestInvalidException;
use pizzashop\shop\domain\service\interfaces\IOrder;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Validator as v;

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
     * @throws CreationFailedException
     */
    public function createOrder(OrderDTO $orderDTO): OrderDTO
    {
        try {
            $commandeEntity = new Order();
            $commandeId = Uuid::uuid4()->toString();
            $commandeEntity->id = $commandeId;
            $commandeEntity->date_commande = date('Y-m-d H:i:s');
            $commandeEntity->etat = Order::ETAT_CREE;
            $montantTotal = 0;
            v::in(Order::TYPE_LIVRAISON)->validate($orderDTO->type_livraison) ? $commandeEntity->type_livraison = $orderDTO->type_livraison : throw new OrderRequestInvalidException();
            v::email()->validate($orderDTO->mail_client) ? $commandeEntity->mail_client = $orderDTO->mail_client : throw new OrderRequestInvalidException();
            v::arrayType()->validate($orderDTO->items) ?: throw new OrderRequestInvalidException();

            foreach ($orderDTO->items as $item) {
                $product = $this->catalogService->readProduct($item->numero);

                $itemEntity = new Item();
                $itemEntity->id = $product->id;
                $itemEntity->libelle = $product->libelle;
                $itemEntity->libelle_taille = $item->taille == 1 ? 'normale' : 'grande';
                $itemEntity->commande_id = $commandeId;

                v::positive()->validate($item->taille) ? $itemEntity->taille = $item->taille : throw new OrderRequestInvalidException();
                v::positive()->validate($item->numero) ? $itemEntity->numero = $item->numero : throw new OrderRequestInvalidException();
                v::positive()->validate($product->quantite) ? $itemEntity->quantite = $item->quantite : throw new OrderRequestInvalidException();

                $this->logger->info('Item créé', $itemEntity->toDTO()->toArray());
                $itemEntity->save();

                $montantTotal += $product->prix * $item->quantite;
            }

            $commandeEntity->montant_total = $montantTotal;
            $commandeDTO = $commandeEntity->toDTO();
            $this->logger->info('Commande créée', $commandeDTO->toArray());
            $commandeEntity->save();
            return $commandeDTO;

        } catch (Exception $e) {
            throw new CreationFailedException();
        }
    }

    /**
     * @throws OrderNotFoundException
     */
    public function readOrder(string $id): OrderDTO
    {
        try {
            $commandeEntity = Order::findOrFail($id);
            $itemsEntity = Item::where('commande_id', '=', $id)->get();
            $arrayItm = [];
            $i = 0;

            foreach ($itemsEntity as $itemEntity) {
                $arrayItm[$i] = $itemEntity->toDTO();
                $i++;
            }
            $commandeEntity->items = $arrayItm;

            return $commandeEntity->toDTO();
        } catch (Exception $e) {
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
            if ($commandeEntity->etatCreation !== Order::ETAT_CREE) {
                throw new OrderRequestInvalidException();
            }
            $commandeEntity->etatCreation = Order::ETAT_VALIDE;
            return $commandeEntity->toDTO();
        } catch (Exception $e) {
            throw new OrderNotFoundException();
        }
    }


}