<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Complete the security steps
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <p>You need to follow some steps with your bank to complete this payment. Let's do it.</p>
                
            </div>
        </div>
    </div>

    <x-slot name="js">
        <script src="https://js.stripe.com/v3/"></script>

        <script>
            const stripe = Stripe('{{ config('services.stripe.key') }}');
            // Variable que viene del controller
            stripe.handleCardAction("{{ $clientSecret }}")
                .then(function (result) {
                    if (result.error) {
                        window.location.replace("{{ route('cancelled') }}");
                    } else {
                        window.location.replace("{{ route('approval') }}");
                    }
                });
        </script>
    </x-slot>
</x-app-layout>