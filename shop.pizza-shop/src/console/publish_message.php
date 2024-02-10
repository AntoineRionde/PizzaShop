<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Faker\Factory;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use pizzashop\shop\domain\entities\order\Order;
use Ramsey\Uuid\Nonstandard\Uuid;

// setupRMQ

function generateRandomOrder(): array
{
    $faker = Factory::create();
    return [
        'id' => Uuid::uuid4(),
        'mail_client' => $faker->email,
        'date_commande' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
        'type_livraison' => rand(1, 3),
        'delai' => rand(0, 10),
        'etat' => Order::CREATED,
        'montant_total' => $faker->randomFloat(2, 10, 100)
    ];
}


$connection = null;
try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
} catch (Exception $e) {
    echo $e->getMessage();
}

$message_queue = 'nouvelles_commandes';
$channel = $connection->channel();

$order = generateRandomOrder();
$jsonOrder = json_encode($order);

$message = new AMQPMessage($jsonOrder);
$channel->basic_publish($message, '', $message_queue);

echo " [x] Sent message: ";
print_r($order);

$channel->close();
try {
    $connection->close();
} catch (Exception $e) {
    echo $e->getMessage();
}
