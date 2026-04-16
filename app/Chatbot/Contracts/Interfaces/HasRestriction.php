<?php
namespace App\ChatBot\Contracts\Interfaces;

interface HasRestriction
{
    public function getRestrictor(): string|array;
}
