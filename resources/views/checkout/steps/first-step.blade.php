<div x-show="step === 1">
    <h2 class="text-xl font-semibold mb-2">Informações Pessoais</h2>
    <p class="text-gray-700 mb-4">Preencha suas informações pessoais para continuar.</p>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="first_name" class="block text-gray-700">Nome</label>
            <input type="text" id="payer_first_name" name="payer[first_name]"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
            <label for="last_name" class="block text-gray-700">Sobrenome</label>
            <input type="text" id="payer_last_name" name="payer[last_name]"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
    </div>
    <div class="mb-4">
        <label for="email" class="block text-gray-700">E-mail</label>
        <input type="email" id="payer_email" name="payer[email]"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
    </div>
    <div class="mb-4">
        <label for="document" class="block text-gray-700">Tipo de documento</label>
        <select id="payer_identification_type" name="payer[identification][type]" type="text"
            class="block w-full border-gray-300 rounded-md shadow-sm"></select>
    </div>
    <div class="mb-4">
        <label for="cpf" class="block text-gray-700">N° do documento</label>
        <input type="text" id="payer.identification.number" name="payer[identification][number]"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
    </div>
</div>
