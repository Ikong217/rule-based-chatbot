<?php
namespace App\ChatBot\Dependencies;

use App\ChatBot\Handlers\Response;
use Illuminate\Support\Facades\Session;

class Conversation
{
    // --- Context (what Response is active) ---

    public function setContext(Response $response): void
    {
        Session::put('chat_context', get_class($response));
    }

    public function getContext(): ?string
    {
        return Session::get('chat_context'); // returns class name string
    }

    public function clearContext(): void
    {
        Session::forget('chat_context');
    }

    // --- Conversation history ---

    public function store(string $userMessage, string $botMessage): void
    {
        $convo   = Session::get('chat_conversation', []);
        $convo[] = [
            ['sender' => 'user', 'message' => $userMessage],
            ['sender' => 'bot',  'message' => $botMessage],
        ];
        Session::put('chat_conversation', $convo);
    }

    public function load(): array
    {
        return Session::get('chat_conversation', []);
    }

    public function clear(): void
    {
        Session::forget('chat_conversation');
    }
}
