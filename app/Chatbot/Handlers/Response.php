<?php
namespace App\ChatBot\Handlers;

use App\Chatbot\Contracts\Interfaces\SmartResponse;
use App\ChatBot\Dependencies\IntentMatcher;
use App\ChatBot\Dependencies\PatternMatcher;

class Response
{
    //default temporary base greeting
    protected array $rules = [
        'hello'       => 'Hello! How can I help you?',
        'hi'          => 'Hi there! What can I do for you?',
        'how are you' => 'I am just a bot, but I am doing great!',
        'bye'         => 'Goodbye! Have a great day!',
        'help'        => 'Sure! Tell me what you need help with.',
    ];

    protected array $errorResponse = [];

    public function __construct()
    {
        ErrorResponses::register($this->errorResponse);
        if (method_exists($this, 'init')) {
            $this->init(); // ← add this
        }
    }

    public function getResponse(string $message): string
    {
        // Check restriction if this response implements HasRestriction
        if ($this instanceof \App\ChatBot\Contracts\Interfaces\HasRestriction) {
            $restrictors = (array) $this->getRestrictor();

            foreach ($restrictors as $restrictorClass) {
                $restrictor = new $restrictorClass();

                if (! $restrictor->passes()) {
                    return $restrictor->errorMessage();
                }
            }
        }

        if ($this instanceof SmartResponse) {
            // pattern match
            $matched = PatternMatcher::match($message, $this->getPatterns());
            if ($matched) {
                return is_callable($matched['handler'])
                    ? $matched['handler']($matched['slots'])
                    : $matched['handler'];
            }

            // intent match
            $intent = IntentMatcher::detect($message, $this->getIntents());
            if ($intent && isset($this->rules[$intent])) {
                $rule = $this->rules[$intent];
                return is_callable($rule) ? $rule($message) : $rule;
            }
        }

        $message = strtolower(trim($message));

        foreach ($this->rules as $keyword => $reply) {
            if (str_contains($message, strtolower($keyword))) {
                return is_callable($reply) ? $reply($message) : $reply;
            }
        }

        return '';
    }

    public function getContextError(): ?string
    {
        return $this->errorResponse['not_found'] ?? null;
    }
}
