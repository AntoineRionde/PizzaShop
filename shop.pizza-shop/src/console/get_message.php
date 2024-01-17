<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../../vendor/autoload.php';

$message_queue = 'nouvelles_commandes';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
$channel = $connection->channel();

try {
    // Déclarer la file d'attente
    $channel->queue_declare($message_queue, false, true, false, false);

    // Définir la fonction de rappel pour traiter les messages
    $callback = function (AMQPMessage $message) {
        // Récupérer et décoder le contenu du message
        $jsonContent = $message->body;
        $decodedContent = json_decode($jsonContent, true);

        // Afficher le contenu dans la console
        echo "Message reçu : " . print_r($decodedContent, true) . PHP_EOL;

        // Acquitter la réception du message
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    };

    // Consommer un message de la file d'attente
    $channel->basic_consume($message_queue, '', false, false, false, false, $callback);

    // Attendre les messages
    while (count($channel->callbacks)) {
        $channel->wait();
    }
} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
} finally {
    // Fermer la connexion
    $channel->close();
    $connection->close();
}
