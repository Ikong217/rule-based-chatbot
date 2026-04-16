<?php
namespace App\ChatBot\Responses;

use App\ChatBot\Handlers\Response;


class CustomerServiceResponse extends Response
{

    public static string $triggerWord = 'customer service';

    protected array $rules = [
        'refund'           => 'To request a refund, please visit our refund page or email support@example.com.',
        'cancel'           => 'You can cancel your subscription from your account settings under Billing.',
        'hours'            => 'Our support team is available Monday to Friday, 9AM to 6PM.',
        'contact'          => 'You can reach us at support@example.com or call 1-800-EXAMPLE.',
        'customer service' => 'Hello! Ask me anything about our services. I am happy to help!',
    ];

}
