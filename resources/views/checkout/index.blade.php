<x-checkout-layout>
    <div x-data="Stepper('{{ route('payment.process') }}', '{{ $course->price }}', '{{ $course->id }}')" x-cloak>
        @include('checkout.components.loading')
        @include('checkout.components.steps')

        <form id="form-checkout" x-transition>
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="my-8">
                @include('checkout.steps.first-step')
                @include('checkout.steps.second-step')
                @include('checkout.steps.third-step', ['course' => $course])
                @include('checkout.steps.fourth-step')
            </div>

            @include('checkout.components.navigation')
        </form>
    </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script defer>
        window.mp = new MercadoPago("{{ config('services.mercado_pago.public_key') }}");

        (async function getIdentificationTypes() {
            try {
                const identificationTypes = await mp.getIdentificationTypes();
                const identificationTypeElement = document.getElementById('payer_identification_type');

                createSelectOptions(identificationTypeElement, identificationTypes);
            } catch (e) {
                return console.error('Error getting identificationTypes: ', e);
            }
        })();

        function createSelectOptions(elem, options, labelsAndKeys = {
            label: "name",
            value: "id"
        }) {
            const {
                label,
                value
            } = labelsAndKeys;

            elem.options.length = 0;

            const tempOptions = document.createDocumentFragment();

            options.forEach(option => {
                const optValue = option[value];
                const optLabel = option[label];

                const opt = document.createElement('option');
                opt.value = optValue;
                opt.textContent = optLabel;

                tempOptions.appendChild(opt);
            });

            elem.appendChild(tempOptions);
        }
    </script>
</x-checkout-layout>
