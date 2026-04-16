<?php
namespace App\ChatBot\Responses;

use App\ChatBot\Contracts\Interfaces\SmartResponse;
use ResourceBundle;

class ProductResponse extends ResourceBundle implements SmartResponse
{
    public static string $triggerWord  = '';
    public static array  $triggerWords = ['product', 'price', 'buy', 'purchase', 'cost', 'cheap'];

    protected array $rules = [
        'product' => 'We have many products! Ask me about a specific one.',
    ];

    public function getPatterns(): array
    {
        return [
            'what is the price of {product}'      => fn($s) => $this->getPrice($s['product']),
            'what can i buy with {amount} dollars' => fn($s) => $this->buyWithBudget($s['amount']),
            'do you have {product}'                => fn($s) => $this->checkStock($s['product']),
            'i want to buy {product}'              => fn($s) => $this->getDetails($s['product']),
        ];
    }

    public function getIntents(): array
    {
        return [
            'buy'   => ['buy', 'purchase', 'afford', 'get', 'order'],
            'cheap' => ['cheap', 'cheapest', 'affordable', 'lowest'],
        ];
    }

    protected function init(): void
    {
        $this->rules['buy']   = fn($m) => $this->buyResponse($m);
        $this->rules['cheap'] = fn($m) => $this->cheapResponse($m);
    }

    private function getPrice(string $name): string
    {
        return "Getting price for: {$name}"; // replace with DB query
    }

    private function buyWithBudget(string $amount): string
    {
        return "Finding products under \${$amount}..."; // replace with DB query
    }

    private function checkStock(string $name): string
    {
        return "Checking stock for: {$name}"; // replace with DB query
    }

    private function getDetails(string $name): string
    {
        return "Getting details for: {$name}"; // replace with DB query
    }

    private function buyResponse(string $message): string
    {
        preg_match('/\d+/', $message, $matches);
        $budget = $matches[0] ?? null;
        return $budget
            ? $this->buyWithBudget($budget)
            : "What's your budget? I'll find the best products for you!";
    }

    private function cheapResponse(string $message): string
    {
        return "Finding our most affordable products..."; // replace with DB query
    }
}
