<?php

require __DIR__ . '/../vendor/autoload.php';

use App\RabbitMQConnection;

$userId = 3;
$queue = "queue.user.$userId";

$connection = RabbitMQConnection::get();
$channel = $connection->channel();

$channel->exchange_declare('chat.direct', 'direct', false, true, false);
$channel->queue_declare($queue, false, true, false, false);
$channel->queue_bind($queue, 'chat.direct', "user.$userId");

echo "Worker User 3 listening...\n";

$channel->basic_consume($queue, '', false, true, false, false, function ($msg) {
    $data = json_decode($msg->body, true);
    echo "[User 3] Message from {$data['from']}: {$data['message']}\n";
});

while ($channel->is_consuming()) {
    $channel->wait();
}
