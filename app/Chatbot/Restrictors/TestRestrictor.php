<?php
namespace App\ChatBot\Restrictors;

use App\ChatBot\Dependencies\BaseRestrictor;


class TestRestrictor extends BaseRestrictor
{
    public function passes(): bool
    {
        // Your logic here — auth check, role check, session, etc.
        return auth()->check(); // only logged in users pass
    }

    public function errorMessage(): string
    {
        return "Sorry, I couldn't tell you that. You must be logged in to access this information.";
    }
}
