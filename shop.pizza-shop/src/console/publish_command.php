<?php

use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../../vendor/autoload.php';

$message_queue = 'nouvelles_commandes';

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');

$channel = $connection->channel();

try {
    $channel->queue_declare($message_queue, false, true, false, false);

    $randomCommand = [
        'command' => 'example_command',
        'data' => 'example_data',
    ];

// Convertir la commande en JSON
    $jsonCommand = json_encode($randomCommand);

    // Publier le message dans la file d'attente
    $message = new AMQPMessage($jsonCommand);
    $channel->basic_publish($message, '', $message_queue);

    // Afficher la commande publiée dans la console
    echo "Commande publiée : " . $jsonCommand . PHP_EOL;
} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
} finally {
    // Fermer la connexion
    $channel->close();
    $connection->close();
}