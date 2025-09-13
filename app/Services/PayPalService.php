<?php
/**
 * Author : Adrean Goh
 */
namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PayPalService
{
    private $client;

    public function __construct()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.secret');

        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($amount, $currency = 'MYR')
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => $amount,
                        ],
                    ]],
                    'application_context' => [
                        'return_url' => route('paypal.success'),
                        'cancel_url' => route('paypal.cancel'),
                    ],

        ];

        return $this->client->execute($request);
    }

    public function captureOrder($orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');
        return $this->client->execute($request);
    }

    
}
