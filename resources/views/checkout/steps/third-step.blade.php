@props(['course'])

<div x-show="step === 3">
    <h2 class="text-xl font-semibold mb-2">Confirmação</h2>
    <p class="text-gray-700 mb-4">Revise os detalhes do seu pedido antes de finalizar a compra.</p>
    <div class="mb-4">
        <h3 class="text-lg font-semibold">Curso</h3>
        <p class="text-gray-700">{{ $course->name }}</p>
        <p class="text-gray-700">{{ $course->description }}</p>
        <div class="flex items-center">
            <span class="text-gray-600 mr-4">Price:</span>
            <span class="text-green-500 font-bold">R$ {{ $course->price }}</span>
        </div>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold">Método de Pagamento</h3>
        <p class="text-gray-700" x-text="paymentMethod === 'card' ? 'Cartão de crédito' : 'Pix'"></p>
    </div>
</div>

