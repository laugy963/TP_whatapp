<?php

require __DIR__ . '/../vendor/autoload.php';

use App\RabbitMQConnection;
use App\MessagePublisher;
use App\MessageValidator;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$errors = MessageValidator::validate($input);

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['error' => $errors]);
    exit;
}

try {
    $connection = RabbitMQConnection::get();
    $publisher = new MessagePublisher($connection);

    $publisher->send($input['from'], $input['to'], $input['message']);

    echo json_encode(['status' => 'ok']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
