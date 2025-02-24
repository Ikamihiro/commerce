<div x-show="!(step === 2 && paymentMethodChose === false)" class="w-full flex items-center" x-data=""
    :class="{ 'justify-between': step > 1, 'justify-end': step === 1 || step === 3 }">
    <button type="button" x-show="step > 1 && step <= 3" @click="prev()"
        class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg" x-transition>Voltar</button>
    <button type="button" x-show="step < 3" @click="next()" class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg"
        x-transition>Próximo</button>
    <button type="submit" id="form-checkout__submit" x-show="step === 3"
        class="text-sm bg-green-500 text-white px-4 py-2 rounded-lg">Finalizar</button>
</div>
