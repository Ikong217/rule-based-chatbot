<?php
namespace App\ChatBot\Handlers;

use App\ChatBot\Contracts\Interfaces\Storable;
use App\ChatBot\Contracts\Traits\HasStorage;
use App\ChatBot\Dependencies\Conversation;
use App\ChatBot\Handlers\Response;

class BaseHandler implements Storable
{
    use HasStorage;

    protected array $responses = [];
    private Response $currentResponder;

    public function __construct()
    {
        $this->conversation = new Conversation();

        // ✅ same structure as child responses
        $this->responses['__main__'] = [
            'class'    => new Response(),
            'triggers' => [],
        ];

        $this->currentResponder = $this->responses['__main__']['class']; // ✅

        $responseFiles = glob(app_path('ChatBot/Responses/*.php'));
        foreach ($responseFiles as $file) {
            $class = 'App\\ChatBot\\Responses\\' . basename($file, '.php');
            if (class_exists($class) && is_subclass_of($class, \App\ChatBot\Handlers\Response::class)) {
                $obj                              = new $class();
                $triggerWord                      = ! empty($class::$triggerWord) ? [$class::$triggerWord] : [];
                $triggerWords                     = ! empty($class::$triggerWords) ? $class::$triggerWords : [];
                $triggers                         = array_unique(array_merge($triggerWord, $triggerWords));
                $this->responses[get_class($obj)] = [
                    'class'    => $obj,
                    'triggers' => $triggers,
                ];
            }
        }
    }

    public function handleResponse(string $message): self
    {
        $lower = strtolower($message);

        // 0. reset
        if ($this->shouldResetContext($lower)) {
            $this->conversation->clearContext();
            $this->currentResponder = $this->responses['__main__']['class'];
            return $this;
        }

        // 1. find trigger
        foreach ($this->responses as $className => $data) {
            if ($className === '__main__') {
                continue;
            }

            foreach ($data['triggers'] as $trigger) {
                if (! empty($trigger) && str_contains($lower, strtolower($trigger))) {
                    $this->currentResponder = $data['class'];
                    // ❌ removed setContext() from here
                    return $this;
                }
            }
        }

        // 2. recall context
        $contextClass = $this->conversation->getContext();
        if ($contextClass) {
            foreach ($this->responses as $className => $data) {
                if ($className === $contextClass) {
                    $this->currentResponder = $data['class'];
                    return $this;
                }
            }
        }

        // 3. fallback
        $this->currentResponder = $this->responses['__main__']['class'];
        return $this;
    }

    public function getResponse(string $message): string
    {
        $response = $this->currentResponder->getResponse($message);

        if ($message == 'get current responder') {
            return get_class($this->currentResponder);
        }

        if ($message === 'get all error responses') {
            $res = "";

            foreach (ErrorResponses::list() as $key => $value) {
                $res .= "{$key} => {$value}\n</br>";
            }
            return $res;
        }

        if (empty($response)) {
            $response = $this->currentResponder->getContextError() ?? ErrorResponses::fetch('not_found');
        } else {
            // ✅ only save context if response was actually successful
            if ($this->currentResponder !== $this->responses['__main__']['class']) {
                $this->conversation->setContext($this->currentResponder);
            }
        }

        $this->saveConversation($message, $response);
        return $response;
    }

    public function streamResponse(string $message): void
    {
        $response = $this->handleResponse($message)->getResponse($message);
        $words    = explode(' ', $response);

        foreach ($words as $word) {
            echo "data: " . json_encode(['word' => $word . ' ']) . "\n\n";
            ob_flush();
            flush();
            usleep(80000);
        }

        echo "data: [DONE]\n\n";
        ob_flush();
        flush();
    }

    private function shouldResetContext(string $message): bool
    {
        $resetWords = ['bye', 'thanks', 'exit', 'quit', 'start over'];
        foreach ($resetWords as $word) {
            if (str_contains($message, $word)) {
                return true;
            }

        }
        return false;
    }
}
