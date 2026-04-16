<?php

namespace App\ChatBot\Responses;

use App\ChatBot\Handlers\Response;

class GreetingResponse extends Response
{
    public static string $triggerWord = 'greeting';

    protected array $rules = [
        'hello'     => 'Hello there! How can I help you today?',
        'hi'        => 'Hi! Welcome. What can I do for you?',
        'hey'       => 'Hey! How can I assist you?',
        'good morning' => 'Good morning! Hope you have a great day. How can I help?',
        'good afternoon' => 'Good afternoon! What can I help you with?',
    ];
}
