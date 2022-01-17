<?php

namespace App\Services;

use App\Http\Traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class StripeService
{
    use ConsumesExternalServices;

    protected $key;
    protected $secret;
    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.stripe.base_uri');
        $this->key = config('services.stripe.key');
        $this->secret = config('services.stripe.secret');
    }

    // Se hace referencia a ellos con un apuntador '&' indicando que estos valores pasan por referencia
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['authorization'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        return "Bearer {$this->secret}";
    }

    public function handlePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
        ]);

        $intent = $this->createIntent($request->value, $request->currency, $request->payment_method);

        session()->put('paymentIntentId', $intent->id);

        return redirect()->route('approval');
    }

    public function handleApproval()
    {
        if (session()->has('paymentIntentId')) {
            $paymentIntentId = session()->get('paymentIntentId');
            $confirmation = $this->confirmPayment($paymentIntentId);

            if ($confirmation->status === 'requires_action') {
                $clientSecret = $confirmation->client_secret;
                return view('stripe.3d-secure', compact('clientSecret'));
            }
            if ($confirmation->status === 'succeeded') {
                $name = $confirmation->charges->data[0]->billing_details->name;
                $currency = strtoupper($confirmation->currency);
                $amount = $confirmation->amount / $this->resolveFactor($currency);

                return redirect()
                    ->route('dashboard')
                    ->withSuccess(['payment' => "Thanks, {$name}. We received your {$amount}{$currency} payment."]);
            }
        }


        return redirect()
            ->route('dashboard')
            ->withErrors('We were unable to confirm your payment. Try again, please');
    }

    public function createIntent($value, $currency, $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                'amount' => round($value * $this->resolveFactor($currency)),
                'currency' => $currency,
                'currency' => strtolower($currency),
                'payment_method' => $paymentMethod,
                'confirmation_method' => 'manual',
            ],
        );
    }

    public function confirmPayment($paymentIntentId)
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntentId}/confirm",
        );
    }

    public function resolveFactor($currency)
    {
        // Lista de monedas que no aceptan decimales
        $zeroDecimalCurrencies = ['JPY'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return 1;
        }
        return 100;
    }

}