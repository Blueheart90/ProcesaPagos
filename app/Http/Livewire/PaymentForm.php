<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Currency;
use App\Models\PaymentPlatform;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;
use App\Resolvers\PaymentPlatformResolver;

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

    protected $paymentPlatformResolver;

    // Al ponerlo como un parametro del constructor/boot crea la instancia de este paymenteresolver
    public function boot(PaymentPlatformResolver $paymentPlatformResolver)
    {

        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }
    

    public function mount()
    {
        $this->value = mt_rand(500, 100000) / 100;
        $this->currencies = Currency::all();
        $this->paymentPlatforms = PaymentPlatform::all();
    }

    public function pay()
    {
        $validatedData  = $this->validate();
        
        $paymentPlatform = $this->paymentPlatformResolver
            ->resolveService($this->payment_platform);
        
        // Añadimos el id de la plataforma de pago al la sesion
        // para que sea utilizado cuando se solicite la aprobación
        session()->put('paymentPlatformId', $this->payment_platform);

        return $paymentPlatform->handlePayment($validatedData);

    }

    public function approval()
    {
        if (session()->has('paymentPlatformId')) {

            $paymentPlatformResolver = new PaymentPlatformResolver;
            $paymentPlatform = $paymentPlatformResolver
                ->resolveService(session()->get('paymentPlatformId'));
    
            return $paymentPlatform->handleApproval();
        }
        
        return redirect()
            ->route('dashboard')
            ->withErrors('We cannot retrieve your payment platform. Try again, please.');
    }

    public function cancelled()
    {
        return redirect()
            ->route('dashboard')
            ->withErrors('You cancelled the payment');
    }



    public function render()
    {
        return view('livewire.payment-form');
    }
}
