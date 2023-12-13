<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function subscribe (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required_without:token_id',
            'exp_month' => 'required_without:token_id',
            'exp_year' => 'required_without:token_id',
            'cvc' => 'required_without:token_id',
            'token_id' => 'required_without:card_number,exp_month,exp_year,cvc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Bad Request',
                'errors' => $validator->errors()
            ], 401);
        }

        if (!$request->has('token_id')) {
            $charge_date = Carbon::today();
            $isMonthsFirst = true;
            $token_id = null;
            $stripe_charge_object = [];
        } else {
            //charge
            $stripe_charge_object = stripe_charge($request->token_id);
            if ($stripe_charge_object->status != 'succeeded') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe charge failed, error: ' . $stripe_charge_object->status,
                    'errors' => []
                ], 401);
            }

            //compute days till next month
            $currentDate = Carbon::today(); // get a new instance of the Carbon class representing today's date
            $daysTillNextMonth = $currentDate->copy()->endOfMonth()->diffInDays($currentDate, true);

            //compute charge date
            $charge_date = ($daysTillNextMonth < 15) ? Carbon::today()->copy()->addMonths(2)->firstOfMonth() : Carbon::today()->copy()->addMonth()->firstOfMonth();
            $isMonthsFirst = false;
            $token_id = $request->token_id;
        }

        //subscribe
        $subscription_response = stripe_subscription($request, $charge_date, $isMonthsFirst);

        if ($subscription_response == false) {
            if (isset($stripe_charge_object) && $stripe_charge_object != []) {
                refund_charge($stripe_charge_object->id);
            }
            return response()->json([
                'success' => false,
                'message' => 'Invalid/Inactive Card provided',
                'errors' => []
            ], 401);
        }

        $subscription = $subscription_response['subscription'];
        $stripe_customer_id = $subscription_response['stripe_customer_id'];
        $stripe_checkout_session_id = $subscription->id;

        if($subscription){
            return response()->json([
                'success' => true,
                'message' => 'Subscription successful.',
                'data' => [
                    'stripe_charge_object' => json_encode($stripe_charge_object),
                    'stripe_customer_id' => $stripe_customer_id,
                    'stripe_checkout_session_id' => $stripe_checkout_session_id,
                ],
            ]);
        }
    }
}
