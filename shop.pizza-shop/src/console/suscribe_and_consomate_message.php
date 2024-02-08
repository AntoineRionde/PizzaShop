<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = null;
try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
} catch (Exception $e) {
}

$message_queue = 'nouvelles_commandes';
$channel = $connection->channel();

$callback = function (AMQPMessage $msg) {
    $msg_body = json_decode($msg->body, true);
    print "[x] message reçu : \n";
    print_r($msg_body);
    $msg->getChannel()->basic_ack($msg->getDeliveryTag());
    print "[x] message traité \n";
};

$msg = $channel->basic_consume($message_queue, '', false, false, false, false, $callback);

try {
    $channel->consume();
} catch (Exception $e) {
    print $e->getMessage();
}
$channel->close();
try {
    $connection->close();
} catch (Exception $e) {
}