<?php

namespace App\Traits;

use Stripe\PaymentIntent;
use Stripe\Stripe;

trait StripePayment
{
    public function generateClientSecret($amount = 0)
    {
        Stripe::setApiKey('sk_test_lUp78O7PgN08WC9UgNRhOCnr');

        try {
            // Create a PaymentIntent with amount and currency
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return $paymentIntent->client_secret;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
