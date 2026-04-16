<?php
namespace App\ChatBot\Contracts\Traits;

use App\ChatBot\Dependencies\Conversation;
use App\ChatBot\Handlers\Response;


trait HasStorage
{
    protected Conversation $conversation;

    protected function bootStorage(): void
    {
        $this->conversation = new Conversation();
    }

    public function saveContext(Response $response): void
    {
        $this->conversation->setContext($response);
    }

    public function loadContext(): ?string
    {
        return $this->conversation->getContext();
    }

    public function saveConversation(string $userMessage, string $botMessage): void
    {
        $this->conversation->store($userMessage, $botMessage);
    }

    public function loadConversation(): array
    {
        return $this->conversation->load();
    }
}
