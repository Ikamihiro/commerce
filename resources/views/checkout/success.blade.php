<x-checkout-layout>
    <section class="my-16">
        <h3 class="text-2xl font-bold mb-0 text-center">Curso comprado com sucesso!</h3>
        <div class="mx-auto p-6">
            <div class="flex items-center mb-8">
                <div class="w-[6rem] h-[6rem]">
                    <img src="{{ $course->cover_url }}" alt="{{ $course->name }}"
                        class="inset-0 h-full transform transition-transform duration-300 hover:scale-105 hover:shadow-xl mx-auto rounded">
                </div>
                <div class="grow flex flex-col justify-center ml-4">
                    <h1 class="text-2xl font-bold mb-0">{{ $course->name }}</h1>
                    <p class="text-sm text-gray-700">
                        {{ $course->description }}
                    </p>
                </div>
            </div>

            <div class="mx-auto text-center">
                <p class="text-gray-700 mb-4">
                    Obrigado por sua compra! Seu pedido foi processado com sucesso. Você receberá um e-mail de confirmação com os detalhes do seu pedido em breve.
                </p>
                <p class="font-bold text-gray-700 mb-8">Se tiver alguma dúvida, entre em contato conosco através da nossa página
                    de
                    contato.</p>
            </div>

            <div class="text-center">
                <a href="{{ route('landing') }}"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Voltar para a loja
                </a>
            </div>
        </div>
    </section>
</x-checkout-layout>
