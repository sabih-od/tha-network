<?php

namespace App\Traits;

use Stripe\StripeClient;

trait StripeTrait
{
    /**
     * Create Stripe Client
     * @param $secret
     * @return \Stripe\AccountLink
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createStripeClient($secret)
    {
        $stripe = new StripeClient($secret);

        $expressAccount = $stripe->accounts->create(['type' => 'express']);

        return $expressAccount;
    }

    public function accountLink($secret, $clientId)
    {
        $stripe = new StripeClient($secret);

        $getLink = $stripe->accountLinks->create([
            'account' => $clientId,
            'refresh_url' => route('vendor-wt-create'),
            'return_url' => route('vendor-wt-index'),
            'type' => 'account_onboarding',
        ]);
        return $getLink;
    }


    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function stripeTransfer($secret, $clientId, $amount)
    {
        $stripe = new StripeClient($secret);

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
