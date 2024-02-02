<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = null;
try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
} catch (Exception $e) {
}

$message_queue = 'nouvelles_commandes';

$channel = $connection->channel();

$msg = $channel->basic_get($message_queue);

if ($msg) {
    echo " [x] Received message: ";
    $messageContent = json_decode($msg->body, true);
    print_r($messageContent);
    echo "\n";

    $channel->basic_ack($msg->getDeliveryTag());
} else {
    echo "No message in queue\n";
}

$channel->close();
try {
    $connection->close();
} catch (Exception $e) {
}