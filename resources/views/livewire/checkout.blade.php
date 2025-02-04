<div>
    <div class="flex justify-between mb-6">
        @foreach ([1, 2, 3, 4] as $step)
            <div class="flex flex-col items-center">
                <div
                    class="w-5 h-5 flex items-center justify-center rounded-full text-white font-bold {{ $currentStep === $step ? 'bg-blue-500' : 'bg-gray-300' }}">
                    <span>{{ $step }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="my-8">
        @if ($currentStep === 1)
            <div id="first-step" x-transition>
                <h2 class="text-xl font-semibold mb-2">Informações Pessoais</h2>
                <p class="text-gray-700 mb-4">Preencha suas informações pessoais para continuar.</p>
                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-gray-700">Nome</label>
                        <input type="text" id="payer_first_name" name="payer[first_name]"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            wire:model="payer.first_name" />
                        @error('payer.first_name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-gray-700">Sobrenome</label>
                        <input type="text" id="payer_last_name" name="payer[last_name]"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                        @error('payer.last_name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">E-mail</label>
                    <input type="email" id="payer_email" name="payer[email]"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                    @error('payer.email')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="document" class="block text-gray-700">Tipo de documento</label>
                    <select id="payer_identification_type" name="payer[identification][type]" type="text"
                        class="block w-full border-gray-300 rounded-md shadow-sm"></select>
                    @error('payer.identification.type')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="cpf" class="block text-gray-700">N° do documento</label>
                    <input type="text" id="payer.identification.number" name="payer[identification][number]"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                    @error('payer.identification.number')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endif
    </div>

    <div
        class="w-full flex items-center {{ ($currentStep === 1 or $currentStep === 4) ? 'justify-end' : 'justify-between' }}">
        @if ($currentStep > 1 && $currentStep <= 4)
            <button type="button" wire:click="back" class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg"
                x-transition>Voltar</button>
        @endif

        @if ($currentStep < 4)
            <button type="button" wire:click="next" class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg"
                x-transition>Próximo</button>
        @endif

        @if ($currentStep === 4)
            <button type="submit" id="form-checkout__submit"
                class="text-sm bg-green-500 text-white px-4 py-2 rounded-lg">Finalizar</button>
        @endif
    </div>
</div>

<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    const mp = new MercadoPago("TEST-6264061c-2825-4fb5-82db-cb37727eba41");

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
