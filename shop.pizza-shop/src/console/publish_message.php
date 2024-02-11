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

    $order = [
        'id' => $faker->uuid,
        'date' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
        'livraisonType' => rand(1, 3),
        'totalAmount' => round(rand(10, 100), 2),
        'clientMail' => $faker->email,
        'delay' => rand(0, 10),
        'items' => [],
    ];

    $numProducts = rand(1, 5);
    for ($i = 0; $i < $numProducts; $i++) {
        $order['items'][] = [
            'id' => $faker->randomNumber(3),
            'numero' => $i + 1,
            'libelle' => $faker->word,
            'taille' => rand(1, 3),
            'libelle_taille' => $faker->randomElement(['petite', 'normale', 'grande']),
            'tarif' => round(rand(5, 20), 2),
            'quantite' => rand(1, 5),
            'commande_id' => $order['id'],
        ];
    }

    return $order;
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
