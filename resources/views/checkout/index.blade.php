<x-checkout-layout>
    <div x-data="Stepper('{{ route('payment.process') }}', '{{ $course->price }}', '{{ $course->id }}')" x-cloak x-init="init()">
        @include('checkout.components.loading')
        {{-- @include('checkout.components.steps') --}}

        <form id="form-checkout" x-transition>
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="my-8">
                <div x-show="confirmed === false">
                    @include('checkout.steps.first-step')
                    @include('checkout.steps.second-step')
                </div>
                {{-- @include('checkout.steps.third-step', ['course' => $course]) --}}
                <div x-show="confirmed === true">
                    @include('checkout.steps.fourth-step')
                </div>
            </div>

            {{-- @include('checkout.components.navigation') --}}
            <div x-show="confirmed === false" class="flex justify-end mt-8">
                <button type="submit" id="submit-button" class="bg-green-500 text-white font-bold py-2 px-4 rounded">
                    Comprar agora
                </button>
            </div>
        </form>
    </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script defer>
        window.mp = new MercadoPago("{{ config('services.mercado_pago.public_key') }}", {
            locale: "pt-BR"
        });

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
