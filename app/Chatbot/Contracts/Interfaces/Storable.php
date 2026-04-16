<?php
namespace App\ChatBot\Contracts\Interfaces;

use App\ChatBot\Handlers\Response;


interface Storable
{
    public function saveContext(Response $response): void;
    public function loadContext(): ?string;
    public function saveConversation(string $userMessage, string $botMessage): void;
    public function loadConversation(): array;
}
