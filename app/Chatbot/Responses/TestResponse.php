<?php

namespace App\ChatBot\Responses;

use App\ChatBot\Contracts\Interfaces\HasRestriction;
use App\ChatBot\Handlers\Response;
use App\ChatBot\Restrictors\TestRestrictor;

class TestResponse extends Response implements HasRestriction
{
    public static string $triggerWord = 'secret';

    protected array $rules = [
        'secret'      => 'Here is the secret info: 1234.',
        'hidden'      => 'This is hidden data only for authorized users.',
    ];

    // Return a single restrictor or an array of restrictors
    public function getRestrictor(): string|array
    {
        return TestRestrictor::class;
        // or multiple: return [TestRestrictor::class, AnotherRestrictor::class];
    }
}
