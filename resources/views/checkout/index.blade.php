<x-checkout-layout>
    <div x-data="Stepper('{{ route('payment.process') }}', '{{ $course->price }}', '{{ $course->id }}')" x-cloak>
        <div class="flex justify-between mb-6">
            <template x-for="num in [1,2,3,4]" :key="num">
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 flex items-center justify-center rounded-full text-white font-bold"
                        :class="step === num ? 'bg-blue-500' : 'bg-gray-300'">
                        <span x-text="num"></span>
                    </div>
                </div>
            </template>
        </div>

        <form id="form-checkout" x-transition>
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="my-8">
                <div x-show="step === 1" x-transition>
                    <h2 class="text-xl font-semibold mb-2">Informações Pessoais</h2>
                    <p class="text-gray-700 mb-4">Preencha suas informações pessoais para continuar.</p>
                    <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-gray-700">Nome</label>
                            <input type="text" id="payer_first_name" name="payer[first_name]"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                        </div>
                        <div>
                            <label for="last_name" class="block text-gray-700">Sobrenome</label>
                            <input type="text" id="payer_last_name" name="payer[last_name]"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700">E-mail</label>
                        <input type="email" id="payer_email" name="payer[email]"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                    </div>
                    <div class="mb-4">
                        <label for="document" class="block text-gray-700">Tipo de documento</label>
                        <select id="payer_identification_type" name="payer[identification][type]" type="text"
                            class="block w-full border-gray-300 rounded-md shadow-sm"></select>
                    </div>
                    <div class="mb-4">
                        <label for="cpf" class="block text-gray-700">N° do documento</label>
                        <input type="text" id="payer.identification.number" name="payer[identification][number]"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                    </div>
                </div>
                <div x-show="step === 2" x-transition>
                    <h2 class="text-xl font-semibold mb-4">Método de Pagamento</h2>
                    <p class="text-gray-700 mb-2">Selecione uma forma de pagamento.</p>
                    <div class="mb-4">
                        <select id="payment_method" name="payment_method"
                            class="block w-full border-gray-300 rounded-md shadow-sm" required x-model="paymentMethod">
                            <option value="card">Cartão de crédito</option>
                            <option value="pix">Pix</option>
                        </select>
                    </div>
                </div>
                <div x-show="step === 3" x-transition>
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
                        <select id="form-checkout__issuer"
                            class="block w-full border-gray-300 rounded-md shadow-sm mb-4"></select>
                        <select id="form-checkout__installments"
                            class="block w-full border-gray-300 rounded-md shadow-sm mb-4"></select>
                    </div>
                    <div x-show="paymentMethod === 'pix'">
                        <p class="text-gray-700 mb-4">Você escolheu pagar com Pix. Após finalizar a compra, você
                            receberá um código QR para realizar o pagamento.</p>
                    </div>
                </div>
                <div x-show="step === 4">
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
                <div x-show="step === 5">
                    <div x-show="paymentMethod === 'card'">
                        <div x-show="paymentData.status !== 'rejected'" class="bg-white shadow-md rounded-lg p-6 mb-12"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-90">
                            <h2 class="text-xl font-semibold mb-2">Compra realizada com sucesso!</h2>
                            <p class="text-gray-700 mb-4">Você receberá um e-mail com os detalhes da sua compra.</p>

                            <button type="button" @click="window.location.href = '/'"
                                class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg">
                                Voltar para o Dashboard
                            </button>
                        </div>

                        <div x-show="paymentData.status === 'rejected'"
                            class="bg-white shadow-md rounded-lg p-6 mb-12"
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
                    </div>

                    <div x-show="paymentMethod === 'pix'">
                        <div class="p-6 space-y-6">
                            <div>
                                <h1 class="text-xl font-medium"
                                    x-text="'Pague R$ ' + paymentData?.transaction_amount + ' via Pix'"></h1>
                            </div>
                            <div x-show="paymentData && paymentData.point_of_interaction">
                                <h2 class="font-medium mb-4">Para pagar, escolha uma destas opções:</h2>
                                <div class="space-y-4">
                                    <p class="text-sm text-gray-600">Código QR</p>
                                    <div class="flex justify-center">
                                        <img x-bind:src="'data:image/jpeg;base64,' + paymentData?.point_of_interaction?.transaction_data?.qr_code_base64"
                                            alt="QR Code" class="w-48 h-48" />
                                    </div>
                                </div>
                                <div class="mt-6 space-y-2">
                                    <p class="text-sm text-gray-600">Código de pagamento</p>
                                    <div class="flex gap-2 items-center">
                                        <input type="text"
                                            x-model="paymentData?.point_of_interaction?.transaction_data?.qr_code || ''"
                                            readonly
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
            <div class="w-full flex items-center" x-data=""
                :class="{ 'justify-between': step > 1, 'justify-end': step === 1 || step === 4 }">
                <button type="button" x-show="step > 1 && step <= 4" @click="prev()"
                    class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg" x-transition>Voltar</button>
                <button type="button" x-show="step < 4" @click="next()"
                    class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg" x-transition>Próximo</button>
                <button type="submit" id="form-checkout__submit" x-show="step === 4"
                    class="text-sm bg-green-500 text-white px-4 py-2 rounded-lg">Finalizar</button>
            </div>
        </form>
    </div>

    <body>
        <script src="https://sdk.mercadopago.com/js/v2"></script>
    </body>
    <script>
        const mp = new MercadoPago("TEST-6264061c-2825-4fb5-82db-cb37727eba41");
        window.mp = mp;

        (async function getIdentificationTypes() {
            try {
                const identificationTypes = await mp.getIdentificationTypes();
                const identificationTypeElement = document.getElementById('payer_identification_type');

                createSelectOptions(identificationTypeElement, identificationTypes);
            } catch (e) {
                return console.error('Error getting identificationTypes: ', e);
            }
        })();

        function createSelectOptions(elem, options, labelsAndKeys = {
            label: "name",
            value: "id"
        }) {
            const {
                label,
                value
            } = labelsAndKeys;

            elem.options.length = 0;

            const tempOptions = document.createDocumentFragment();

            options.forEach(option => {
                const optValue = option[value];
                const optLabel = option[label];

                const opt = document.createElement('option');
                opt.value = optValue;
                opt.textContent = optLabel;

                tempOptions.appendChild(opt);
            });

            elem.appendChild(tempOptions);
        }

        // function stepper() {
        //     return {
        //         step: 1,
        //         paymentMethod: 'card',
        //         paymentData: {},
        //         next() {
        //             if (!this.validateStep()) {
        //                 return;
        //             }
        //             this.step > 3 ? null : this.step = this.step + 1;
        //             if (this.step === 3) {
        //                 this.buildPaymentForm();
        //             }
        //         },
        //         prev() {
        //             this.step < 2 ? null : this.step = this.step - 1;
        //         },
        //         validateStep() {
        //             if (this.step === 1) {
        //                 return this.validatePersonalInfo();
        //             }
        //             if (this.step === 2) {
        //                 return this.validatePaymentMethod();
        //             }
        //             return true;
        //         },
        //         validatePersonalInfo() {
        //             const firstName = document.getElementById('payer_first_name').value;
        //             const lastName = document.getElementById('payer_last_name').value;
        //             const email = document.getElementById('payer_email').value;
        //             const documentNumber = document.getElementById('payer.identification.number').value;
        //             const identificationType = document.getElementById('payer_identification_type').value;

        //             if (!firstName || !lastName || !email || !documentNumber || !identificationType) {
        //                 alert('Preencha todos os campos');
        //                 return false;
        //             }

        //             return true;
        //         },
        //         validatePaymentMethod() {
        //             const paymentMethod = document.getElementById('payment_method').value;

        //             if (!paymentMethod) {
        //                 alert('Selecione um método de pagamento');
        //                 return false;
        //             }

        //             return true;
        //         },
        //         buildPaymentForm() {
        //             if (this.paymentMethod === 'card') {
        //                 this.buildCardForm();
        //             } else {
        //                 this.buildPixForm();
        //             }
        //         },
        //         buildCardForm() {
        //             const cardForm = mp.cardForm({
        //                 amount: @json($course->price),
        //                 iframe: true,
        //                 form: {
        //                     id: "form-checkout",
        //                     cardNumber: {
        //                         id: "form-checkout__cardNumber",
        //                         placeholder: "Número do cartão",
        //                     },
        //                     expirationDate: {
        //                         id: "form-checkout__expirationDate",
        //                         placeholder: "MM/YY",
        //                     },
        //                     securityCode: {
        //                         id: "form-checkout__securityCode",
        //                         placeholder: "Código de segurança",
        //                     },
        //                     cardholderName: {
        //                         id: "form-checkout__cardholderName",
        //                         placeholder: "Titular do cartão",
        //                     },
        //                     issuer: {
        //                         id: "form-checkout__issuer",
        //                         placeholder: "Banco emissor",
        //                     },
        //                     installments: {
        //                         id: "form-checkout__installments",
        //                         placeholder: "Parcelas",
        //                     },
        //                 },
        //                 callbacks: {
        //                     onFormMounted: error => {
        //                         if (error) {
        //                             return console.warn("Form Mounted handling error: ", error);
        //                         }
        //                         console.log("Form mounted");
        //                     },
        //                     onSubmit: event => {
        //                         event.preventDefault();

        //                         const {
        //                             paymentMethodId: payment_method_id,
        //                             issuerId: issuer_id,
        //                             amount,
        //                             token,
        //                             installments,
        //                         } = cardForm.getCardFormData();

        //                         fetch('{{ route('payment.process') }}', {
        //                             method: "POST",
        //                             headers: {
        //                                 "Content-Type": "application/json",
        //                             },
        //                             body: JSON.stringify({
        //                                 token,
        //                                 issuer_id,
        //                                 payment_method_id,
        //                                 transaction_amount: Number(amount),
        //                                 installments: Number(installments),
        //                                 course_id: @json($course->id),
        //                                 payer: {
        //                                     first_name: document.getElementById('payer_first_name').value,
        //                                     last_name: document.getElementById('payer_last_name').value,
        //                                     email: document.getElementById('payer_email').value,
        //                                     identification: {
        //                                         type: document.getElementById(
        //                                             'payer_identification_type').value,
        //                                         number: document.getElementById(
        //                                             'payer.identification.number').value,
        //                                     },
        //                                 },
        //                             }),
        //                         }).then(response => response.json()).then(data => {
        //                             this.step = 5;
        //                             this.paymentData = data.data;
        //                         }).catch(error => {
        //                             console.error(error);
        //                         });
        //                     },
        //                     onFetching: (resource) => {
        //                         console.log("Fetching resource: ", resource);
        //                     }
        //                 },
        //             });
        //         },
        //         buildPixForm() {
        //             console.log('Pix form');
        //             let checkoutForm = document.getElementById("form-checkout");

        //             checkoutForm.addEventListener("submit", (e) => {
        //                 e.preventDefault();

        //                 fetch('{{ route('payment.process') }}', {
        //                     method: "POST",
        //                     headers: {
        //                         "Content-Type": "application/json",
        //                     },
        //                     body: JSON.stringify({
        //                         payment_method_id: 'pix',
        //                         course_id: @json($course->id),
        //                         payer: {
        //                             first_name: document.getElementById('payer_first_name').value,
        //                             last_name: document.getElementById('payer_last_name').value,
        //                             email: document.getElementById('payer_email').value,
        //                             identification: {
        //                                 type: document.getElementById(
        //                                     'payer_identification_type').value,
        //                                 number: document.getElementById(
        //                                     'payer.identification.number').value,
        //                             },
        //                         },
        //                     }),
        //                 }).then(response => response.json()).then(data => {
        //                     this.step = 5;
        //                     console.log(data);
        //                     this.paymentData = data.data;
        //                 }).catch(error => {
        //                     console.error(error);
        //                 });
        //             });
        //         }
        //     }
        // }
    </script>
</x-checkout-layout>
