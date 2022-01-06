<div class= "m-4 ">

    <form id="paymentForm" wire:submit.prevent="pay">
        <div class="flex">
            <div class="mr-2 ">
                <label for="value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">How much you want to pay?</label>
                <input
                    wire:model='value' 
                    type="number" 
                    min="5"
                    step="0.01"
                    id="value"
                    name="value"                  
                    class="text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">

            </div>
            <div class="mb-6 mr-6">

                <label for="currency" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Currency</label>
                <select wire:model="currency" id="currency" name="currency" class="text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                @foreach ($currencies as $currency)
                    
                    <option value="{{ $currency->iso }}">{{ strtoupper($currency->iso) }}</option>
                @endforeach

                </select>
                @error('currency')
                    <strong class="text-sm text-red-600 ">{{ $message }}</strong>
                @enderror
            </div>
            @error('value')
                <strong class="text-sm text-red-600 ">{{ $message }}</strong>
            @enderror


        </div>
        <span class="text-sm text-gray-400 ">Use values with  up yo two decimal positions, using dot "."</span>

        <div class="mt-4 ">
            <label for="value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Select the desired payment platforms:</label>
            <div>
                <div class="flex items-center">
                    @foreach ($paymentPlatforms as $paymentPlatform)
                        <input wire:model='payment_platform' value="{{ $paymentPlatform->id }}" type="radio"  name="payment_platform" checked>
                        <img class="h-8 ml-3" src="{{ asset($paymentPlatform->image) }}" alt="">
                    @endforeach
                </div>
                @error('payment_platform')
                    <strong class="text-sm text-red-600 ">{{ $message }}</strong>
                @enderror
            </div>
        </div>
        <div x-data="{ open: @entangle('payment_platform') }">
            @foreach ($paymentPlatforms as $paymentPlatform)
                <div x-show="open == {{ $paymentPlatform['id']}}" class="mt-4 ">
                    @includeIf('components.' . strtolower($paymentPlatform->name) . '-collapse')
                </div>
            @endforeach
        </div>

        <button id="payButton" type="submit" class="block px-4 py-2 mt-4 text-sm text-white bg-blue-500 rounded-md hover:bg-blue-600">Pay</button>
    </form>
</div>
