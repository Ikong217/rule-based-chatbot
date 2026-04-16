<?php
namespace App\ChatBot\Responses;

use App\ChatBot\Contracts\Interfaces\SmartResponse;
use App\ChatBot\Dependencies\ResponseBuilder;
use App\ChatBot\Handlers\Response;

class ShippingResponse extends Response implements SmartResponse
{
    public static string $triggerWord = '';
    public static array $triggerWords = [
        'shipping', 'delivery', 'ship', 'deliver',
        'arrive', 'track', 'package', 'order',
    ];

    protected array $rules = [
        'ship'     => 'We ship worldwide! Standard delivery takes 3-5 business days.',
        'delivery' => 'We offer standard and express delivery options.',
        'track'    => 'You can track your order using the tracking number sent to your email.',
        'arrive'   => 'Standard orders arrive in 3-5 days. Express in 1-2 days.',
        'order'    => 'You can place an order through our website or contact our support team.',
    ];

    public function getPatterns(): array
    {
        return [
            'how long does {item} take to arrive' => fn($s) => $this->arrivalTime($s['item']),
            'how long does delivery take'         => fn($s)         => $this->deliveryTime(),
            'how much is shipping to {location}'  => fn($s)  => $this->shippingCost($s['location']),
            'do you ship to {location}'           => fn($s)           => $this->shipsTo($s['location']),
            'where is my {item}'                  => fn($s)                  => $this->trackItem($s['item']),
            'can i change my {item} address'      => fn($s)      => $this->changeAddress($s['item']),
        ];
    }

    public function getIntents(): array
    {
        return [
            'track'  => ['track', 'where is', 'locate', 'find my', 'status'],
            'cost'   => ['how much', 'cost', 'price', 'fee', 'charge'],
            'time'   => ['how long', 'when', 'arrive', 'take', 'days'],
            'change' => ['change', 'update', 'modify', 'edit', 'cancel'],
        ];
    }

    protected function init(): void
    {
        $this->rules['track']  = fn($m) => $this->trackIntent($m);
        $this->rules['cost']   = fn($m) => $this->costIntent($m);
        $this->rules['time']   = fn($m) => $this->timeIntent($m);
        $this->rules['change'] = fn($m) => $this->changeIntent($m);
    }

    // --- Pattern Handlers ---

    private function arrivalTime(string $item): string
    {
        return ResponseBuilder::start()
            ->line("Your {$item} typically arrives in 3-5 business days for standard shipping.")
            ->line("Need it faster? Express shipping delivers in 1-2 business days.")
            ->closing()
            ->build("\n");
    }

    private function deliveryTime(): string
    {
        return ResponseBuilder::start()
            ->line("Here are our delivery options:")
            ->list([
                'Standard — 3 to 5 business days',
                'Express  — 1 to 2 business days',
                'Overnight — next business day',
            ])
            ->closing()
            ->build("\n");
    }

    private function shippingCost(string $location): string
    {
        $costs = [
            'us'        => 'Free shipping on orders over $50, otherwise $5.99.',
            'canada'    => 'Flat rate of $12.99 to Canada.',
            'uk'        => 'Flat rate of $14.99 to the UK.',
            'australia' => 'Flat rate of $19.99 to Australia.',
        ];

        $lower = strtolower($location);
        $cost  = collect($costs)->first(fn($v, $k) => str_contains($lower, $k));

        return $cost
            ? ResponseBuilder::start()->line($cost)->closing()->build()
            : "Shipping to {$location} is calculated at checkout based on weight and distance.";
    }

    private function shipsTo(string $location): string
    {
        $noShip = ['russia', 'iran', 'north korea'];
        $lower  = strtolower($location);

        if (collect($noShip)->contains(fn($c) => str_contains($lower, $c))) {
            return "Sorry, we currently do not ship to {$location}.";
        }

        return ResponseBuilder::start()
            ->line("Yes! We ship to {$location}.")
            ->line("Delivery times and costs will vary by location.")
            ->closing()
            ->build("\n");
    }

    private function trackItem(string $item): string
    {
        return ResponseBuilder::start()
            ->line("To track your {$item}:")
            ->list([
                'Check your email for a tracking number',
                'Visit our website and go to Order Tracking',
                'Enter your tracking number to see live updates',
            ])
            ->closing()
            ->build("\n");
    }

    private function changeAddress(string $item): string
    {
        return ResponseBuilder::start()
            ->line("To change your {$item} address:")
            ->list([
                'Contact us within 24 hours of placing your order',
                'Email support@example.com with your order number',
                'We cannot change addresses once the order has shipped',
            ])
            ->closing()
            ->build("\n");
    }

    // --- Intent Handlers ---

    private function trackIntent(string $message): string
    {
        return ResponseBuilder::start()
            ->line("To track your order:")
            ->list([
                'Check your confirmation email for a tracking number',
                'Visit our Order Tracking page on the website',
                'Contact support if you cannot find your tracking number',
            ])
            ->closing()
            ->build("\n");
    }

    private function costIntent(string $message): string
    {
        return ResponseBuilder::start()
            ->line("Our shipping rates:")
            ->list([
                'Standard (3-5 days) — $5.99 or free over $50',
                'Express (1-2 days)  — $14.99',
                'Overnight           — $24.99',
            ])
            ->closing()
            ->build("\n");
    }

    private function timeIntent(string $message): string
    {
        return $this->deliveryTime();
    }

    private function changeIntent(string $message): string
    {
        return ResponseBuilder::start()
            ->line("Need to make changes to your order?")
            ->list([
                'Changes must be made within 24 hours of ordering',
                'Email support@example.com with your order number',
                'We cannot modify orders that have already shipped',
            ])
            ->closing()
            ->build("\n");
    }

    protected array $errorResponse = [
        'shipping_not_found' => "Sorry, I didn't quite catch that. If you have more questions about shipping, feel free to ask or contact our support team!",
    ];

}
