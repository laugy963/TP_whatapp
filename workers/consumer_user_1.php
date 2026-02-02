<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/RabbitMQConnection.php';

use App\RabbitMQConnection;

$userId = 1;
$queue = "queue.user.$userId";

// Vérifier que la classe existe
if (!class_exists('RabbitMQConnection')) {
    die("Erreur : la classe RabbitMQConnection n'est pas définie.\n");
}

$connection = RabbitMQConnection::get();
$channel = $connection->channel();

$channel->exchange_declare('chat.direct', 'direct', false, true, false);
$channel->queue_declare($queue, false, true, false, false);
$channel->queue_bind($queue, 'chat.direct', "user.$userId");

echo "Worker User 1 listening...\n";

$channel->basic_consume($queue, '', false, true, false, false, function ($msg) {
    $data = json_decode($msg->body, true);
    echo "[User 1] Message from {$data['from']}: {$data['message']}\n";
});

while ($channel->is_consuming()) {
    $channel->wait();
}