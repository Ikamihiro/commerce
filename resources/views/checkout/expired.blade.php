<x-checkout-layout>
    <div class="flex flex-col items-center justify-center min-h-[300px]">
        {{-- <svg class="w-16 h-16 text-red-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
            <line x1="8" y1="8" x2="16" y2="16" stroke="currentColor" stroke-width="2"/>
            <line x1="16" y1="8" x2="8" y2="16" stroke="currentColor" stroke-width="2"/>
        </svg> --}}
        <i class="ri-error-warning-fill text-red-500 text-6xl mb-4"></i>
        <h1 class="text-2xl font-bold mb-2">Checkout expirado</h1>
        <p class="text-center text-gray-600 mb-4">O tempo para finalizar sua compra expirou. Por favor, inicie o processo novamente.</p>
        <a href="{{ route('landing') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Voltar para a loja
        </a>
    </div>
</x-checkout-layout>
