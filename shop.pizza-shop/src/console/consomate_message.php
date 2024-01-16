<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Connect to RabbitMQ server
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
$channel = $connection->channel();

// Declare the queue if it doesn't exist
$channel->queue_declare('nouvelles_commandes', false, false, false, false);

// Consume a message from the queue in blocking mode
echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function (AMQPMessage $msg) {
    echo " [x] Received message: ";
    $messageContent = json_decode($msg->body, true);
    print_r($messageContent);
    echo "\n";

    // Acknowledge the message reception
    $msg->getChannel()->basic_ack($msg->getDeliveryTag());
};
$channel->basic_consume('nouvelles_commandes', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();