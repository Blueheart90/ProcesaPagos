<?php

namespace App\Services;

use App\Http\Traits\ConsumesExternalServices;

class CurrencyConversionService
{
    use ConsumesExternalServices;

    protected $baseUri;
    protected $apiKey;


    public function __construct()
    {
        $this->baseUri = config('services.currency_conversion.base_uri');
        $this->apiKey = config('services.currency_conversion.api_key');
    }

    // Se hace referencia a ellos con un apuntador '&' indicando que estos valores pasan por referencia
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $queryParams['apiKey'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        return $this->apiKey;
    }

    public function convertCurrency($from, $to)
    {
        $response = $this->makeRequest(
            'GET',
            '/api/v7/convert',
            [
                'q' => "{$from}_{$to}",
                'compact' => 'ultra'
            ]
        );
        // retorna el factor de conversion
        return $response->{strtoupper("{$from}_{$to}")};
    }



 

}