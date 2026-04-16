<?php

namespace App\ChatBot\Handlers;

class ErrorResponses
{
    protected static array $errors = [
        'not_found'    => 'Sorry, I did not understand that.',
        'unauthorized' => 'You do not have permission to access this data.',
        'system_error' => 'Oops! Something went wrong. Please try again later.',
    ];

    // Add or merge errors
    public static function register(array $errors)
    {
        self::$errors = array_merge(self::$errors, $errors);
    }

    // Fetch a single error by key
    public static function fetch(string $key): string
    {
        return self::$errors[$key] ?? self::$errors['system_error'];
    }

    public static function list(): array
    {
        return self::$errors;
    }
}
