<?php
namespace App\ChatBot\Dependencies;

class CommandHandler
{
    protected array $commands = [];

    public function __construct()
    {
        // Register all commands here
        $this->registerCommands();
    }

    /**
     * Map command strings to callable functions
     */
    protected function registerCommands()
    {
        $this->commands = [
            '/storenewfakeuser' => function($args = []) {
                // Example: create a fake user for testing
                $name = $args[0] ?? 'FakeUser';
                $email = $args[1] ?? 'fakeuser@example.com';

                // Normally here you would call your User model or factory
                // For demo:
                return "Created new user: {$name} ({$email})";
            },

            '/clearchat' => function() {
                // Clear all sessions/conversations
                session()->forget('chat_conversation');
                return "Chat session cleared!";
            },

            '/roll' => function($args = []) {
                $sides = $args[0] ?? 6;
                return "Admin rolled: " . rand(1, $sides);
            },
        ];
    }

    /**
     * Check if message is an admin command
     */
    public function isAdminCommand(string $message): bool
    {
        return str_starts_with($message, '/');
    }

    /**
     * Execute the command
     */
    public function execute(string $message): string
    {
        // Split message into command + args
        $parts = explode(' ', $message);
        $cmd = strtolower($parts[0]);
        $args = array_slice($parts, 1);

        if (isset($this->commands[$cmd])) {
            $func = $this->commands[$cmd];
            if (is_callable($func)) {
                return $func($args);
            }
        }

        return "Unknown admin command: {$cmd}";
    }
}
