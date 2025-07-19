<x-checkout-layout>
    <section class="my-16">
        <h3 class="text-2xl font-bold mb-0 text-center">Compra cancelada</h3>
        <div class="mx-auto p-6 text-center">
            <p class="text-gray-700 mb-4">
                Sentimos muito que você tenha decidido cancelar sua compra. Se houve algum problema ou se pudermos ajudar de alguma forma, por favor, entre em contato conosco através da nossa página de contato.
            </p>
            <p class="text-gray-700 mb-8">
                Esperamos vê-lo novamente em breve! Caso queira, você pode tentar novamente ou escolher outro curso que lhe interesse.
            </p>

            <div class="text-center">
                <a href="{{ route('landing') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Voltar para a loja
                </a>
            </div>
        </div>
    </section>
</x-checkout-layout>
