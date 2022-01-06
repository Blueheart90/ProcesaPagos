<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Currency;
use App\Models\PaymentPlatform;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;

class PaymentForm extends Component
{
    public $currency = 'eur';
    public $value, $payment_platform;


    public $currencies, $paymentPlatforms;

    protected $rules = [
        'value' => 'required|numeric|min:5',
        'currency' => 'required|exists:currencies,iso',
        'payment_platform' => 'required|exists:payment_platforms,id',
    ];

    public function mount()
    {
        $this->value = mt_rand(500, 100000) / 100;
        $this->currencies = Currency::all();
        $this->paymentPlatforms = PaymentPlatform::all();
    }

    public function pay()
    {
        $validatedData  = $this->validate();
        
        Log::debug("prueba desde pay " . $this->currency);

        $paymentPlatform = resolve(PayPalService::class);

        return $paymentPlatform->handlePayment($validatedData);

    }



    public function render()
    {
        return view('livewire.payment-form');
    }
}
