<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../../vendor/autoload.php';

$exchange_name = 'pizzashop';
$queue_name = 'nouvelles_commandes';
$routing_key = 'nouvelle';
$connection = null;
try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
} catch (Exception $e) {
}
$channel = $connection->channel();
$channel->exchange_declare($exchange_name, 'direct', false, true, false);
$channel->queue_declare($queue_name, false, true, false, false);
$channel->queue_bind($queue_name, $exchange_name, $routing_key);
$channel->close();
try {
    $connection->close();
} catch (Exception $e) {
}