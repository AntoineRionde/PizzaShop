<?php

namespace pizzashop\tests\order;

use Faker\Factory;
use Illuminate\Database\Capsule\Manager as DB;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\entities\order\Item;
use pizzashop\shop\domain\entities\order\Order;
use pizzashop\shop\domain\exception\OrderRequestInvalidException;
use pizzashop\shop\domain\service\classes\CatalogService;
use pizzashop\shop\domain\service\classes\OrderService;
use Ramsey\Uuid\Uuid;

class ServiceCommandeTest extends TestCase
{

    private static $orderIds = [];
    private static $itemIds = [];
    private static $productService;
    private static $orderService;
    private static $faker;

    private static $logger;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $dbcom = __DIR__ . '/../../config/commande.db.ini';
        $dbcat = __DIR__ . '/../../config/catalog.db.ini';
        $db = new DB();
        $db->addConnection(parse_ini_file($dbcom), 'commande');
        $db->addConnection(parse_ini_file($dbcat), 'catalog');
        $db->setAsGlobal();
        $db->bootEloquent();

        self::$logger = new Logger('CommandeLogger');
        self::$productService = new CatalogService();
        self::$orderService = new OrderService(self::$productService, self::$logger);
        self::$faker = Factory::create('fr_FR');
        self::createOrder();
        self::createValidateOrder();
    }

    private static function createOrder()
    {
        $id = Uuid::uuid4()->toString();
        $order = new Order();
        $order->id = $id;
        $order->date_commande = self::$faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s');
        $order->etat = Order::ETAT_CREE;
        $order->montant_total = self::$faker->randomFloat(2, 10, 100);
        $order->type_livraison = self::$faker->randomElement(Order::TYPE_LIVRAISON);
        $order->mail_client = self::$faker->email;
        $order->save();
        self::$orderIds[] = $id;

        $nbItems = self::$faker->numberBetween(1, 5);
        for ($i = 0; $i < $nbItems; $i++) {
            $id = 9999 + $i;
            $item = new Item();
            $item->id = $id;
            $item->libelle = self::$faker->word;
            $item->libelle_taille = self::$faker->randomElement(['normale', 'grande']);
            $item->commande_id = $order->id;
            $item->tarif = self::$faker->randomFloat(2, 10, 100);
            $item->taille = self::$faker->numberBetween(1, 2);
            $item->numero = self::$faker->numberBetween(1, 10);
            $item->quantite = self::$faker->numberBetween(1, 5);
            $item->save();
            self::$itemIds[] = $id;
        }
    }

    private static function createValidateOrder()
    {
        $id = Uuid::uuid4()->toString();
        $order = new Order();
        $order->id = $id;
        $order->date_commande = self::$faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s');
        $order->etat = Order::ETAT_VALIDE;
        $order->montant_total = self::$faker->randomFloat(2, 10, 100);
        $order->type_livraison = self::$faker->randomElement(Order::TYPE_LIVRAISON);
        $order->mail_client = self::$faker->email;
        $order->save();
        self::$orderIds[] = $id;

        $nbItems = self::$faker->numberBetween(1, 5);
        for ($i = 0; $i < $nbItems; $i++) {
            $id = 99999 + $i;
            $item = new Item();
            $item->id = $id;
            $item->libelle = self::$faker->word;
            $item->libelle_taille = self::$faker->randomElement(['normale', 'grande']);
            $item->commande_id = $order->id;
            $item->tarif = self::$faker->randomFloat(2, 10, 100);
            $item->taille = self::$faker->numberBetween(1, 2);
            $item->numero = self::$faker->numberBetween(1, 10);
            $item->quantite = self::$faker->numberBetween(1, 5);
            $item->save();
            self::$itemIds[] = $id;
        }
    }

    public function testAccederCommande()
    {
        $id = self::$orderIds[0];
        $orderEntity = Order::find($id);
        $orderDTO = self::$orderService->readOrder($id);

        // Assertions
        $this->assertNotNull($orderDTO);
        $this->assertEquals($orderEntity->id, $orderDTO->id);
        $this->assertEquals($orderEntity->date_commande, $orderDTO->date_commande);
        $this->assertEquals($orderEntity->etat, $orderDTO->etat);
        $this->assertEquals($orderEntity->montant_total, $orderDTO->montant_total);
        $this->assertEquals($orderEntity->type_livraison, $orderDTO->type_livraison);
        $this->assertEquals($orderEntity->mail_client, $orderDTO->mail_client);
        $this->assertCount($orderEntity->items()->get()->count(), $orderDTO->items);
    }

    public function testValidateOrderSuccess()
    {
        $orderId = self::$orderIds[0];
        $validatedOrderDTO = self::$orderService->validateOrder($orderId);

        // Assertions
        $this->assertInstanceOf(OrderDTO::class, $validatedOrderDTO);
        $this->assertEquals(Order::ETAT_VALIDE, $validatedOrderDTO->etat);
    }

    public function testValidateOrderFailure()
    {
        $orderId = self::$orderIds[1];

        // Assertions
        $this->expectException(OrderRequestInvalidException::class);
        self::$orderService->validateOrder($orderId);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::cleanDB();
    }

    private static function cleanDB()
    {
        foreach (self::$orderIds as $id) {
            Order::find($id)->delete();
        }
        foreach (self::$itemIds as $id) {
            Item::find($id)->delete();
        }
    }
}