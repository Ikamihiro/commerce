<div x-show="step === 2" x-transition>
    <div x-show="paymentMethodChose === false">
        <h2 class="text-xl font-semibold mb-4">Método de Pagamento</h2>
        <p class="text-gray-700 mb-2">Selecione uma forma de pagamento.</p>
        <div class="mb-4 flex space-x-4">
            <div @click="selectPaymentMethod('card');"
                class="flex-1 cursor-pointer p-4 border rounded-md shadow-sm flex flex-col items-center space-x-2"
                :class="{ 'border-blue-500 border-2': paymentMethod === 'card' }">
                <i class="ri-bank-card-fill ri-3x text-gray-700 mb-2"></i>
                <span class="text-gray-700">Cartão de crédito</span>
            </div>
            <div @click="selectPaymentMethod('pix');"
                class="flex-1 cursor-pointer p-4 border rounded-md shadow-sm flex flex-col items-center space-x-2"
                :class="{ 'border-blue-500 border-2': paymentMethod === 'pix' }">
                <i class="ri-pix-fill ri-3x text-gray-700 mb-2"></i>
                <span class="text-gray-700">Pix</span>
            </div>
        </div>
    </div>

    <div x-show="paymentMethodChose === true">
        <div x-show="paymentMethod === 'card'">
            <div id="form-checkout__cardNumber"
                class="!h-[2.75rem] block w-full border border-gray-300 rounded-md shadow-sm mb-4 p-[.75rem]">
            </div>
            <div id="form-checkout__expirationDate"
                class="!h-[2.75rem] block w-full border border-gray-300 rounded-md shadow-sm mb-4 p-[.75rem]">
            </div>
            <div id="form-checkout__securityCode"
                class="!h-[2.75rem] block w-full border border-gray-300 rounded-md shadow-sm mb-4 p-[.75rem]">
            </div>
            <input type="text" id="form-checkout__cardholderName"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm mb-4" />
            <select id="form-checkout__issuer" class="block w-full border-gray-300 rounded-md shadow-sm mb-4"></select>
            <select id="form-checkout__installments"
                class="block w-full border-gray-300 rounded-md shadow-sm mb-4"></select>
        </div>
        <div x-show="paymentMethod === 'pix'">
            <p class="text-gray-700 mb-4">Você escolheu pagar com Pix. Após finalizar a compra, você
                receberá um código QR para realizar o pagamento.</p>
        </div>
    </div>
</div>
