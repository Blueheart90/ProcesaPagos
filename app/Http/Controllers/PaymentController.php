<?php

namespace App\Http\Controllers;

use App\Resolvers\PaymentPlatformResolver;
use App\Services\PayPalService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    
    public function __construct()
    {
        $this->middleware('auth');

    }

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
