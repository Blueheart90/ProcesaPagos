
<label class="mt-3 mb-4" for="">Card details</label>

<div class="flex">
    <div class=" mr-2">
        <input class="text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " type="text" id="cardNumber" data-checkout="cardNumber" placeholder="Card Number">
    </div>

    <div class=" mr-2">
        <input class="text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " type="text" data-checkout="securityCode" placeholder="CVC">
    </div>

    <div class=" mr-2">
        <input class="text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " type="text" data-checkout="cardExpirationMonth" placeholder="MM">
    </div>

    <div class=" mr-2">
        <input class="text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " type="text" data-checkout="cardExpirationYear" placeholder="YY">
    </div>
</div>

<div class="flex mt-2">
    <div class="mr-2">
        <input class="text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " type="text" data-checkout="cardholderName" placeholder="Your Name">
    </div>
    <div class="mr-2">
        <input class="text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " type="email" data-checkout="cardholderEmail" placeholder="email@example.com" name="email">
    </div>
</div>


<div class="flex mt-2">
    <div class="mr-2">
        <select class="custom-select" data-checkout="docType"></select>
    </div>
    <div class="mr-2">
        <input class="text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " type="text" data-checkout="docNumber" placeholder="Document">
    </div>
</div>

<div class="flex mt-2">
    <div class="">
        <small class="form-text text-mute"  role="alert" >Your payment will be converted to {{ strtoupper(config('services.mercadopago.base_currency')) }}</small>
    </div>
</div>


<x-slot name='js'>
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
    <script>
        const mercadoPago = window.Mercadopago;

        mercadoPago.setPublishableKey('{{ config('services.mercadopago.public_key') }}');
        
        mercadoPago.getIdentificationTypes();

    </script>



</x-slot>