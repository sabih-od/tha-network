<?php

namespace App\Http\Controllers;

use App\Traits\StripePayment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HowItWorks extends Controller
{
    use StripePayment;

    public function __construct()
    {
        $this->middleware('is.validate.code');
    }

    public function show()
    {
        return Inertia::render('HowItWorks');
    }

    public function paymentShow()
    {
        return Inertia::render('Payment', [
            'client_secret' => null //$this->generateClientSecret(29.99)
        ]);
    }


}
