<?php
namespace App\ChatBot\Dependencies;

abstract class BaseRestrictor
{
    abstract public function passes(): bool;

    public function errorMessage(): string
    {
        return "Sorry, I couldn't share that information. You do not have enough authority to access this.";
    }
}
