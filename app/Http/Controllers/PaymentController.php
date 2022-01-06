<?php

namespace App\Http\Controllers;

use App\Services\PayPalService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function approval()
    {
 
        $paymentPlatform = resolve(PayPalService::class);

        return $paymentPlatform->handleApproval();
        
    }

    public function cancelled()
    {
        return redirect()
            ->route('dashboard')
            ->withErrors('You cancelled the payment');
    }
}
