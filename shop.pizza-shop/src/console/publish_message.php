<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// setupRMQ

function generateRandomOrder(): array
{
    $products = ['ProductA', 'ProductB', 'ProductC'];
    $randomProduct = $products[array_rand($products)];

    return [
        'product' => $randomProduct,
        'quantity' => rand(1, 10),
        'customer' => 'Customer' . rand(1, 1000),
    ];
}


$connection = null;
try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
} catch (Exception $e) {
}
$channel = $connection->channel();


$channel->queue_declare('nouvelles_commandes', false, false, false, false);

$order = generateRandomOrder();
$jsonOrder = json_encode($order);

$message = new AMQPMessage($jsonOrder);
$channel->basic_publish($message, '', 'nouvelles_commandes');

echo " [x] Sent message: ";
print_r($order);

$channel->close();
try {
    $connection->close();
} catch (Exception $e) {
}
