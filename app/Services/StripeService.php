<?php

namespace App\Services;

use App\Http\Traits\ConsumesExternalServices;

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

    public function handlePayment($request)
    {

    }

    public function handleApproval()
    {

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