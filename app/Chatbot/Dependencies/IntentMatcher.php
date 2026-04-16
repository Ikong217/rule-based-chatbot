<?php
namespace App\ChatBot\Dependencies;

class IntentMatcher
{
    /**
     * "purchase", "buy", "get", "afford" → all resolve to "buy"
     */
    public static function detect(string $message, array $intents): ?string
    {
        $lower = strtolower($message);

        foreach ($intents as $intent => $synonyms) {
            foreach ($synonyms as $synonym) {
                if (str_contains($lower, $synonym)) {
                    return $intent; // returns "buy", "refund", etc.
                }
            }
        }

        return null;
    }
}
