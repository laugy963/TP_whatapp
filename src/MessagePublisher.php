<?php

namespace App;

use PhpAmqpLib\Message\AMQPMessage;

class MessagePublisher
{
    private $channel;
    private string $exchange = 'chat.direct';

    public function __construct($connection)
    {
        $this->channel = $connection->channel();

        $this->channel->exchange_declare(
            $this->exchange,
            'direct',
            false,
            true,
            false
        );
    }

    public function send(int $from, int $to, string $message): void
    {
        $payload = json_encode([
            'from' => $from,
            'to' => $to,
            'message' => $message,
            'timestamp' => time()
        ]);

        $msg = new AMQPMessage($payload, [
            'content_type' => 'application/json',
            'delivery_mode' => 2
        ]);

        $routingKey = "user.$to";

        $this->channel->basic_publish($msg, $this->exchange, $routingKey);
    }
}
