<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\RewardLog;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $res = $this->getRewardLogsByRange(Carbon::now()->startOfYear(), Carbon::now()->endOfYear());
        $rewards_this_year = $res['reward_logs'];
        $total_reward_amount_this_year = $res['total'];

        $res = $this->getRewardLogsByRange(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
        $rewards_this_month = $res['reward_logs'];
        $total_reward_amount_this_month = $res['total'];

        $res = $this->getSubscriptionPayments(date('Y'));
        $incoming_payments_this_year = $res['payments'];
        $total_payments_this_year = $res['total'];

        $res = $this->getSubscriptionPayments(date('Y'), date('m'));
        dd('done');
        $incoming_payments_this_month = $res['payments'];
        $total_payments_this_month = $res['total'];

        return view('admin.dashboard', compact('total_reward_amount_this_year', 'total_reward_amount_this_month', 'total_payments_this_year', 'total_payments_this_month'));
    }

    public function getRewardLogsByRange ($start, $end) {
        $reward_logs = RewardLog::with('reward.user', 'reward.invited_user')
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->get();

        $total = 0;
        foreach ($reward_logs as $reward_log) {
            if ($reward_log->reward) {
                $total += $reward_log->reward->amount;
            }
        }

        return [
            'reward_logs' => $reward_logs,
            'total' => $total,
        ];
    }

    public function getSubscriptionPayments ($year, $month = null)
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET_KEY')
        );

        // Fetch all subscriptions using Stripe API
        $subscriptions = $stripe->subscriptions->all(['limit' => 100000]);

        // Array to store subscription payments for the current year
        $subscriptionPayments = [];

        foreach ($subscriptions->data as $subscription) {
            // Fetch the invoices for each subscription
            $invoices = $stripe->invoices->all(['subscription' => $subscription->id, 'limit' => 100000]);

            foreach ($invoices->data as $invoice) {
                // Check if the invoice is paid and the payment was made in the current year
                $invoiceYear = date('Y', $invoice->created);
                if (!is_null($month)) {
                    $invoiceMonth = date('m', $invoice->created);
                    if ($invoice->paid && $invoiceYear === $year && $invoiceMonth === $month) {
                        $subscriptionPayments[] = $invoice;
                    }
                } else {
                    if ($invoice->paid && $invoiceYear === $year) {
                        $subscriptionPayments[] = $invoice;
                    }
                }
            }
        }

        $total = 0.0;
        foreach ($subscriptionPayments as $payment) {
//            $total += ($payment->total / 100);
            // -$2.00
            $total += ($payment->total / 100) - 2;
        }

        // Output the subscription payments for the current year
        return [
            'payments' => $subscriptionPayments,
            'total' => $total,
        ];
    }

}
