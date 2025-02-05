<x-checkout-layout>
    <div x-data="Stepper('{{ route('payment.process') }}', '{{ $course->price }}', '{{ $course->id }}')" x-cloak>
        @include('checkout.components.steps')

        <form id="form-checkout" x-transition>
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="my-8">
                @include('checkout.steps.first-step')
                @include('checkout.steps.second-step')
                @include('checkout.steps.third-step', ['course' => $course])
                @include('checkout.steps.fourth-step')
            </div>

            <div class="w-full flex items-center" x-data=""
                :class="{ 'justify-between': step > 1, 'justify-end': step === 1 || step === 3 }">
                <button type="button" x-show="step > 1 && step <= 3" @click="prev()"
                    class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg" x-transition>Voltar</button>
                <button type="button" x-show="step < 3" @click="next()"
                    class="text-sm bg-blue-500 text-white px-4 py-2 rounded-lg" x-transition>Próximo</button>
                <button type="submit" id="form-checkout__submit" x-show="step === 3"
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
