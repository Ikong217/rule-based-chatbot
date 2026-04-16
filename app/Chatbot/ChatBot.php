<?php
namespace App\Chatbot;

use App\ChatBot\Handlers\BaseHandler;


class ChatBot
{
    private $messages = [];
    private $handler;

    public function __construct()
    {
        $this->handler = new BaseHandler();
    }

    // public function getResponse($message){
    //     $this->messages[] = ['sender'=>'user','message'=>$message];
    //     $reply = $this->handler->getResponse($message);
    //     $this->messages[] = ['sender'=>'bot','message'=>$reply];
    //     return $reply;
    // }

    public function getResponse(string $message): string
    {
        return $this->handler
            ->handleResponse($message)
            ->getResponse($message);
    }

    public function streamResponse(string $message): void
    {
        $this->handler->streamResponse($message);
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
