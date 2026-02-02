<?php
namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnection
{
    public static function get()
    {
        $host = 'dog.lmq.cloudamqp.com';
        $port = 5671;
        $user = 'tqaoitos';
        $password = 'JUsqKoAdXa_lqeQ2Q59I3FfueYa3UpyX';
        $vhost = 'tqaoitos';

        $sslOptions = [
            'verify_peer' => true,
            'verify_peer_name' => true,
        ];

        return new AMQPStreamConnection(
            $host,
            $port,
            $user,
            $password,
            $vhost,
            false,      // insist
            'AMQPLAIN', // login_method
            null,       // login_response
            'en_US',    // locale
            3.0,        // connection_timeout
            3.0,        // read_write_timeout
            null,       // context
            true,       // keepalive
            null,       // heartbeat
            $sslOptions // ssl_options
        );
    }
}