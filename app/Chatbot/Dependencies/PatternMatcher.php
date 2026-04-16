<?php
namespace App\ChatBot\Dependencies;

class PatternMatcher
{
    public static function toRegex(string $pattern): string
    {
        $escaped = preg_quote($pattern, '/');
        $regex   = preg_replace('/\\\{(\w+)\\\}/', '(?P<$1>.+?)', $escaped);
        // Remove ^ and $ anchors, and allow extra words around the pattern
        return '/' . $regex . '/i';
    }

    public static function match(string $message, array $patterns): ?array
    {
        $message = trim($message, " \t\n\r\0\x0B?!.,"); // strip punctuation

        foreach ($patterns as $pattern => $handler) {
            $regex = self::toRegex($pattern);
            if (preg_match($regex, $message, $matches)) {
                $slots = array_filter(
                    $matches,
                    fn($k) => is_string($k),
                    ARRAY_FILTER_USE_KEY
                );
                // Strip punctuation from each slot value too
                $slots = array_map(fn($v) => trim($v, " \t\n\r\0\x0B?!.,"), $slots);
                return [
                    'handler' => $handler,
                    'slots'   => $slots,
                ];
            }
        }
        return null;
    }
}
