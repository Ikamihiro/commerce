{{-- <div x-show="step === 1"> --}}
<h2 class="text-xl font-semibold mb-2">Informações Pessoais</h2>
<p class="text-gray-700 mb-4">Preencha suas informações pessoais para continuar.</p>
<div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        {{-- <label for="first_name" class="block text-gray-700">Nome</label> --}}
        <input type="text" id="payer_first_name" name="payer[first_name]"
            class="block w-full border-gray-300 rounded-md shadow-sm"
            placeholder="Primeiro nome"/>
        <div x-show="errors['payer_first_name']">
            <p class="text-red-500 text-sm mt-1">O nome é obrigatório.</p>
        </div>
    </div>
    <div>
        {{-- <label for="last_name" class="block text-gray-700">Sobrenome</label> --}}
        <input type="text" id="payer_last_name" name="payer[last_name]"
            class="block w-full border-gray-300 rounded-md shadow-sm"
            placeholder="Sobrenome"/>
        <div x-show="errors['payer_last_name']">
            <p class="text-red-500 text-sm mt-1">O sobrenome é obrigatório.</p>
        </div>
    </div>
</div>
<div class="mb-4">
    {{-- <label for="email" class="block text-gray-700">E-mail</label> --}}
    <input type="email" id="payer_email" name="payer[email]"
        class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="E-mail" />
    <div x-show="errors['payer_email']">
        <p class="text-red-500 text-sm mt-1">O e-mail é obrigatório.</p>
    </div>
</div>
<div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        {{-- <label for="document" class="block text-gray-700">Tipo de documento</label> --}}
        <select id="payer_identification_type" name="payer[identification][type]" type="text"
            class="block w-full border-gray-300 rounded-md shadow-sm"></select>
        <div x-show="errors['payer_identification_type']">
            <p class="text-red-500 text-sm mt-1">O tipo de documento é obrigatório.</p>
        </div>
    </div>
    <div>
        {{-- <label for="cpf" class="block text-gray-700">N° do documento</label> --}}
        <input type="text" id="payer_identification_number" name="payer[identification][number]"
            class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Número do documento" />
        <div x-show="errors['payer_identification_number']">
            <p class="text-red-500 text-sm mt-1">O número do documento é obrigatório.</p>
        </div>
    </div>
</div>
{{-- </div> --}}
