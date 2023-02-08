<?php

namespace App\Traits;

use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

trait StripePayment
{
    public function generateClientSecret($amount = 0)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

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

    public function createStripeClient($secret = "sk_test_lUp78O7PgN08WC9UgNRhOCnr")
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        $expressAccount = $stripe->accounts->create(['type' => 'express']);

        return $expressAccount;
    }

    public function accountLink($secret = "sk_test_lUp78O7PgN08WC9UgNRhOCnr", $clientId)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        $getLink = $stripe->accountLinks->create([
            'account' => $clientId,
            'refresh_url' => route('vendor-wt-create'),
            'return_url' => route('vendor-wt-index'),
            'type' => 'account_onboarding',
        ]);
        return $getLink;
    }

    public function get_account($account_id)
    {

    }


    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function stripeTransfer($secret = "sk_test_lUp78O7PgN08WC9UgNRhOCnr", $clientId, $amount)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        try {
            $transfer = $stripe->transfers->create([
                "amount" => $amount * 100,
                "currency" => "usd",
                "destination" => $clientId,
            ]);

            return $transfer;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
