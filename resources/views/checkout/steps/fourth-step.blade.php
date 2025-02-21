<div x-show="step === 4">
    <div x-show="paymentMethod === 'card'">
        <div x-show="paymentData.status === 'authorized'" class="p-6 mb-12"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90">
            <h2 class="text-xl font-semibold mb-2">Compra realizada com sucesso!</h2>
            <p class="text-gray-700 mb-4">Você receberá um e-mail com os detalhes da sua compra.</p>

            <button type="button" @click="window.location.href = '/'"
                class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg">
                Voltar para o Dashboard
            </button>
        </div>

        <div x-show="paymentData.status === 'rejected'" class="p-6 mb-12"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90">
            <h2 class="text-xl font-semibold mb-2">Erro ao processar o pagamento</h2>
            <p class="text-gray-700 mb-4">Ocorreu um erro ao processar o pagamento. Tente novamente.
            </p>

            <button type="button" @click="window.location.reload()"
                class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg">
                Tentar Novamente
            </button>
        </div>

        <div x-show="paymentData.status === 'failed'" class="p-6 mb-12"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90">
            <h2 class="text-xl font-semibold mb-2">Erro ao processar o pagamento</h2>
            <p class="text-gray-700 mb-4">Ocorreu um erro ao processar o pagamento. Espere um pouco e tente novamente.
            </p>

            <button type="button" @click="window.location.reload()"
                class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg">
                Tentar Novamente
            </button>
        </div>
    </div>

    <div x-show="paymentMethod === 'pix'">
        <div x-show="paymentData.status === 'failed'" class="p-6 mb-12"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90">
            <h2 class="text-xl font-semibold mb-2">Erro ao processar o pagamento</h2>
            <p class="text-gray-700 mb-4">Ocorreu um erro ao processar o pagamento. Espere um pouco e tente novamente.
            </p>

            <button type="button" @click="window.location.reload()"
                class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg">
                Tentar Novamente
            </button>
        </div>

        <div x-show="paymentData.status !== 'failed'">
            <div class="p-6 space-y-6">
                <div>
                    <h1 class="text-xl font-medium" x-text="'Pague R$ ' + paymentData?.transaction_amount + ' via Pix'">
                    </h1>
                </div>
                <div x-show="paymentData && paymentData.point_of_interaction">
                    <h2 class="font-medium mb-4">Para pagar, escolha uma destas opções:</h2>
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">Código QR</p>
                        <div class="flex justify-center">
                            <img x-bind:src="'data:image/jpeg;base64,' + paymentData?.point_of_interaction?.transaction_data
                                ?.qr_code_base64"
                                alt="QR Code" class="w-48 h-48" />
                        </div>
                    </div>
                    <div class="mt-6 space-y-2">
                        <p class="text-sm text-gray-600">Código de pagamento</p>
                        <div class="flex gap-2 items-center">
                            <input type="text"
                                x-model="paymentData?.point_of_interaction?.transaction_data?.qr_code || ''" readonly
                                class="bg-gray-50 p-2 text-sm rounded border flex-1 text-gray-600" />
                            <button class="text-blue-600 p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <h2 class="font-medium mb-4">Como pagar?</h2>
                    <ol class="space-y-6">
                        <li class="flex gap-4">
                            <div
                                class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center flex-shrink-0">
                                1</div>
                            <p class="text-gray-600">Entre no app ou site do seu banco e escolha a opção de
                                pagamento via Pix.</p>
                        </li>
                        <li class="flex gap-4">
                            <div
                                class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center flex-shrink-0">
                                2</div>
                            <p class="text-gray-600">Escaneie o código QR ou copie e cole o código de
                                pagamento.
                            </p>
                        </li>
                        <li class="flex gap-4">
                            <div
                                class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center flex-shrink-0">
                                3</div>
                            <p class="text-gray-600">Pronto! O pagamento será creditado na hora e você
                                receberá um
                                e-mail de confirmação.</p>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="p-6 border-t">
                <p class="text-sm text-gray-500">O Pix tem um limite diário de transferências. Para mais
                    informações, por favor, consulte seu banco.</p>
            </div>
        </div>
    </div>
</div>
