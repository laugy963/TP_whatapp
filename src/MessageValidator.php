<?php

namespace App;

class MessageValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['from']) || !is_numeric($data['from'])) {
            $errors[] = 'Invalid "from"';
        }

        if (!isset($data['to']) || !is_numeric($data['to'])) {
            $errors[] = 'Invalid "to"';
        }

        if (!isset($data['message']) || trim($data['message']) === '') {
            $errors[] = 'Invalid "message"';
        }

        return $errors;
    }
}
