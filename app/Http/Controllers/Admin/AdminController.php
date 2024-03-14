<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminWithdrawal;
use App\Models\Customers;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Reward;
use App\Models\RewardLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class AdminController extends Controller
{
    public function dashboard()
    {
        $res = $this->getRewardLogsByRange(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
        $rewards_this_month = $res['reward_logs'];
        $total_reward_amount_this_month = $res['total'];
//
        $res = $this->getRewardLogsByRange(Carbon::now()->startOfYear(), Carbon::now()->endOfYear());
        $rewards_this_year = $res['reward_logs'];
        $total_reward_amount_this_year = $res['total'];
//
        $res = $this->getSubscriptionPayments(date('Y'), date('m'));
        $incoming_payments_this_month = $res['payments'];
        $total_payments_this_month = $res['total'];
        $gross_total_payments_this_month = $res['gross_total'];
        $count_of_payments_this_month = $res['payments_count'];
//

        $res = $this->getSubscriptionPayments(date('Y'));
        $incoming_payments_this_year = $res['payments'];
        $total_payments_this_year = $res['total'];
        $gross_total_payments_this_year = $res['gross_total'];
        $count_of_payments_this_year = $res['payments_count'];

        $admin_withdrawals_this_month = AdminWithdrawal::whereDate('date', '>=', Carbon::now()->startOfMonth())
            ->whereDate('date', '<=', Carbon::now()->endOfMonth())
            ->get();
        $admin_withdrawals_this_year = AdminWithdrawal::whereDate('date', '>=', Carbon::now()->startOfYear())
            ->whereDate('date', '<=', Carbon::now()->endOfYear())
            ->get();
        $total_admin_withdrawals_this_month = AdminWithdrawal::whereDate('date', '>=', Carbon::now()->startOfMonth())
            ->whereDate('date', '<=', Carbon::now()->endOfMonth())
            ->sum('amount');
        $total_admin_withdrawals_this_year = AdminWithdrawal::whereDate('date', '>=', Carbon::now()->startOfYear())
            ->whereDate('date', '<=', Carbon::now()->endOfYear())
            ->sum('amount');
//
        return view('admin.dashboard',
            compact(
                'total_reward_amount_this_year',
                'total_reward_amount_this_month',
                'total_payments_this_year',
                'total_payments_this_month',
                'gross_total_payments_this_month',
                'gross_total_payments_this_year',
                'rewards_this_year',
                'rewards_this_month',
                'incoming_payments_this_month',
                'incoming_payments_this_year',
                'count_of_payments_this_month',
                'count_of_payments_this_year',
                'admin_withdrawals_this_month',
                'admin_withdrawals_this_year',
                'total_admin_withdrawals_this_month',
                'total_admin_withdrawals_this_year',
            )
        );
//        return view('admin.dashboard');
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

        $subscription_ids = get_active_subscription_ids();
        $payment_count = 0;
        $subscriptionPayments = [];
        foreach ($subscription_ids as $subscription_id) {
            try {
                $inviter = get_inviter_by_subscription_id($subscription_id);

                $invoices = $stripe->invoices->all(['subscription' => $subscription_id, 'limit' => 100000]);

                foreach ($invoices->data as $invoice) {
                    if ($invoice->paid == false || $invoice->total < 2999) {
                        continue;
                    }

                    $customer = $stripe->customers->retrieve($invoice->customer);
                    $charge = $stripe->charges->retrieve($invoice->charge);
                    // Check if the invoice is paid and the payment was made in the current year
                    $invoiceYear = date('Y', $invoice->effective_at);
                    if (!is_null($month)) {
                        $invoiceMonth = date('m', $invoice->effective_at);
                        if ($invoice->paid && $invoiceYear === $year && $invoiceMonth === $month) {
                            $invoice['user'] = User::where('stripe_customer_id', $invoice->customer)->first();
                            $invoice['total'] = ($invoice['total'] / 100);
//                            $invoice['formatted_date'] = date('m', $invoice->effective_at).'-'.date('d', $invoice->effective_at).'-'.date('Y', $invoice->effective_at);
                            $invoice['formatted_date'] = (Carbon::parse($charge->created)->format('M d, Y.')) ?? '';
                            $invited_by = Reward::where('on_inviting' , $invoice['user']->id)->with('user')->first();
                            $invoice['is_company_invite'] = isset($invited_by) && $invited_by->user->role_id == 1;
                            $subscriptionPayments[] = $invoice;
                            $payment_count += 1;
                        }
                    } else {
                        if ($invoice->paid && $invoiceYear === $year) {
                            $invoice['user'] = User::where('stripe_customer_id', $invoice->customer)->first();
                            $invoice['total'] = ($invoice['total'] / 100);
//                            $invoice['formatted_date'] = date('m', $invoice->effective_at).'-'.date('d', $invoice->effective_at).'-'.date('Y', $invoice->effective_at);
                            $invoice['formatted_date'] = (Carbon::parse($charge->created)->format('M d, Y.'));
                            $invited_by = Reward::where('on_inviting' , $invoice['user']->id)->with('user')->first();
                            $invoice['is_company_invite'] = isset($invited_by) && $invited_by->user->role_id == 1;
                            $subscriptionPayments[] = $invoice;
                            $payment_count += 1;
                        }
                    }
                }
            } catch (ApiErrorException $e) {
                Log::info('function getSubscriptionPayments failed. $subscription_id: ' . $subscription_id . ', error: ' . $e->getMessage());
                continue;
            }
        }

        //include charges (first time)
        $users_with_charge_object = get_user_with_charge_object();
        foreach ($users_with_charge_object as $user_with_charge_object) {
            try {
                $inviter = get_inviter_by_user_id($user_with_charge_object->id);

                $decoded_charge_object = json_decode($user_with_charge_object->stripe_charge_object);
                if ($decoded_charge_object->paid == false || $decoded_charge_object->amount < 2999) {
                    continue;
                }

                // Check if the invoice is paid and the payment was made in the current year
                $charge_year = date('Y', $decoded_charge_object->created);
                if (!is_null($month)) {
                    $charge_month = date('m', $decoded_charge_object->created);
                    if ($charge_year === $year && $charge_month === $month) {
                        $decoded_charge_object->total = ($decoded_charge_object->amount / 100);
                        $decoded_charge_object->date = $decoded_charge_object->created;
                        $decoded_charge_object->user = $user_with_charge_object;
//                        $decoded_charge_object->formatted_date = date('m', $decoded_charge_object->created).'-'.date('d', $decoded_charge_object->created).'-'.date('Y', $decoded_charge_object->created);
                        $decoded_charge_object->formatted_date = (Carbon::parse($decoded_charge_object->created)->format('M d, Y.'));
                        $invited_by = Reward::where('on_inviting' , $decoded_charge_object->user->id)->with('user')->first();
                        $decoded_charge_object->is_company_invite = isset($invited_by) && $invited_by->user->role_id == 1;;
                        $subscriptionPayments[] = $decoded_charge_object;
                        $payment_count += 1;
                    }
                } else {
                    if ($charge_year === $year) {
                        $decoded_charge_object->total = ($decoded_charge_object->amount / 100);
                        $decoded_charge_object->date = $decoded_charge_object->created;
                        $decoded_charge_object->user = $user_with_charge_object;
//                        $decoded_charge_object->formatted_date = date('m', $decoded_charge_object->created).'-'.date('d', $decoded_charge_object->created).'-'.date('Y', $decoded_charge_object->created);
                        $decoded_charge_object->formatted_date = (Carbon::parse($decoded_charge_object->created)->format('M d, Y.'));
                        $invited_by = Reward::where('on_inviting' , $decoded_charge_object->user->id)->with('user')->first();
                        $decoded_charge_object->is_company_invite = isset($invited_by) && $invited_by->user->role_id == 1;;
                        $subscriptionPayments[] = $decoded_charge_object;
                        $payment_count += 1;
                    }
                }
            } catch (\Exception $e) {
                Log::info('function getSubscriptionPayments failed. $user_with_charge_object: ' . $user_with_charge_object . ', error: ' . $e->getMessage());
                continue;
            }
        }


        $total = 0.0;
        $gross_total = 0.0;
        foreach ($subscriptionPayments as $payment) {
//            $total += ($payment->total / 100);
            // -$2.00
//            $total += ($payment->total / 100) - 2;
//            $total += $payment->total;

            $payment->gross_total = $payment->total;
            $payment->total = isset($payment->is_company_invite) && $payment->is_company_invite ? $payment->total - 1.99 : ($payment->total - 11.99);

            $total += $payment->total;
            $gross_total += $payment->gross_total;
        }

        // Output the subscription payments for the current year
        return [
            'payments' => $subscriptionPayments,
            'total' => $total,
            'gross_total' => $gross_total,
            'payments_count' => $payment_count,
        ];
    }

}
