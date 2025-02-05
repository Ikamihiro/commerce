<div x-show="step === 2" x-transition>
    <div x-show="paymentMethodChose === false">
        <h2 class="text-xl font-semibold mb-4">Método de Pagamento</h2>
        <p class="text-gray-700 mb-2">Selecione uma forma de pagamento.</p>
        <div class="mb-4">
            <select id="payment_method" name="payment_method" class="block w-full border-gray-300 rounded-md shadow-sm"
                required x-model="paymentMethod">
                <option value="card">Cartão de crédito</option>
                <option value="pix">Pix</option>
            </select>
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
