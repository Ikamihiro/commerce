<x-landing-layout>
    <section class="my-16">
        <h3 class="text-2xl font-bold mb-8 text-center">Contato</h3>
        <div class="mx-auto p-6 bg-white shadow-md rounded">

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf

                <div class="flex flex-col md:flex-row w-full items-center">
                    <div class="w-full mb-4 grow mr-0 md:mr-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Nome completo">
                        @error('name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full mb-4 grow ml-0 md:ml-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Seu melhor e-mail">
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700">Assunto</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Assunto da mensagem">
                    @error('subject')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700">Mensagem</label>
                    <textarea id="message" name="message" rows="5"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('message') }}</textarea>
                    @error('message')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="text-right">
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Enviar
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-landing-layout>
