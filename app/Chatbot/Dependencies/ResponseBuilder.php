<?php
namespace App\ChatBot\Dependencies;

class ResponseBuilder
{
    private array $parts = [];

    public static function start(): self
    {
        return new self();
    }

    public function greet(): self
    {
        $this->parts[] = collect([
            'Hello!', 'Hi there!', 'Hey!'
        ])->random();
        return $this;
    }

    public function line(string $text): self
    {
        $this->parts[] = $text;
        return $this;
    }

    public function list(array $items): self
    {
        if (empty($items)) return $this;
        $this->parts[] = implode("\n", array_map(fn($i) => "• $i", $items));
        return $this;
    }

    public function when(bool $condition, string $text): self
    {
        if ($condition) $this->parts[] = $text;
        return $this;
    }

    public function closing(): self
    {
        $this->parts[] = collect([
            'Let me know if you need anything else!',
            'Happy to help further!',
            'Feel free to ask more questions!',
        ])->random();
        return $this;
    }

    public function build(string $separator = ' '): string
    {
        return implode($separator, array_filter($this->parts));
    }
}
