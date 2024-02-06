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
     * @throws OrderRequestInvalidException
     */
    public function createOrder(OrderDTO $orderDTO): OrderDTO
    {
        try {
            $orderEntity = new Order();
            $orderId = Uuid::uuid4()->toString();
            $orderEntity->id = $orderId;
            $orderEntity->date_commande = date('Y-m-d H:i:s');
            $orderEntity->etat = Order::CREATED;
            $orderEntity->delai = $orderDTO->delay;

            v::in(Order::LIVRAISON_TYPE)->validate($orderDTO->livraisonType) ? $orderEntity->type_livraison = $orderDTO->livraisonType : throw new OrderRequestInvalidException("Livraison type is invalid");
            v::email()->validate($orderDTO->clientMail) ? $orderEntity->mail_client = $orderDTO->clientMail : throw new OrderRequestInvalidException("Email is invalid");
            v::arrayType()->validate($orderDTO->items) ?: throw new OrderRequestInvalidException("Items should be an array");

            $totalAmount = 0;
            foreach ($orderDTO->items as $item) {
                $itemEntity = new Item();
                v::positive()->validate($item->number) ? $itemEntity->taille = $item->size : throw new OrderRequestInvalidException("Size is invalid");
                v::positive()->validate($item->number) ? $itemEntity->numero = $item->number : throw new OrderRequestInvalidException("Product number is invalid");
                v::positive()->validate($item->quantity) ? $itemEntity->quantite = $item->quantity : throw new OrderRequestInvalidException("Quantity is invalid");

                $product = $this->catalogService->getProductByNumber($item->number);

                $itemEntity->libelle_taille = $item->size === 1 ? "normale" : "grande";
                $itemEntity->tarif = $product->prices->{$itemEntity->libelle_taille};
                $itemEntity->libelle = $product->label;

                $itemEntity->commande_id = $orderId;

                $this->logger->info('Item créé', $itemEntity->toDTO()->toArray());
                $itemEntity->save();
                $totalAmount += $itemEntity->tarif * $item->quantity;
            }

            $orderEntity->montant_total = $totalAmount;
            $orderDTO = $orderEntity->toDTO();
            $this->logger->info('Commande créée', $orderDTO->toArray());

            $orderEntity->save();
            return $orderDTO;
        } catch (OrderRequestInvalidException $e) {
            throw new OrderRequestInvalidException($e->getMessage());
        } catch (Exception) {
            throw new CreationFailedException();
        }
    }

    public function readAllOrders(): array
    {
        $commandes = Order::all();
        $commandesDTO = [];
        foreach ($commandes as $commande) {
            $commandesDTO[] = $commande->toDTO();
        }
        return $commandesDTO;
    }

    /**
     * @throws OrderNotFoundException
     */
    public function readOrder(string $id): OrderDTO
    {
        try {
            $commandeEntity = Order::findOrFail($id);

            return $commandeEntity->toDTO();
        } catch (Exception $e) {
            throw new OrderNotFoundException();
        }
    }

    /**
     * @throws OrderNotFoundException
     * @throws OrderRequestInvalidException
     */
    public function validateOrder(string $id): OrderDTO
    {
        try {
            $commandeEntity = Order::findOrFail($id);
            if ($commandeEntity->etat !== Order::CREATED) {
                throw new OrderRequestInvalidException("Order is not in CREATED state.");
            }
            $commandeEntity->etat = Order::VALIDATED;
            $commandeEntity->save();
            return $commandeEntity->toDTO();
        } catch (OrderRequestInvalidException $e) {
            throw new OrderRequestInvalidException($e->getMessage());
        } catch (Exception) {
            throw new OrderNotFoundException();
        }
    }
}