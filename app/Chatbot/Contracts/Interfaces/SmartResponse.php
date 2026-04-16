<?php
namespace App\ChatBot\Contracts\Interfaces;

interface SmartResponse
{
    public function getPatterns(): array;
    public function getIntents(): array;
}
