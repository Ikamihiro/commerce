function validate() { }

export function Stepper(processRoute, coursePrice, courseId) {
    return {
        step: 1,
        paymentMethod: 'card',
        paymentMethodChose: false,
        paymentData: {},
        cardForm: {},
        next() {
            if (!this.validateStep()) {
                return;
            }

            if (this.step === 2 && this.paymentMethodChose === false) {
                this.buildPaymentForm();
                return;
            }

            this.step = this.step + 1;

        },
        prev() {
            if (this.step === 2 && this.paymentMethodChose === true) {
                this.paymentMethodChose = false;
                return;
            }

            this.step = this.step - 1;
        },
        validateStep() {
            if (this.step === 1) {
                return this.validatePersonalInfo();
            }
            if (this.step === 2) {
                return this.validatePaymentMethod();
            }
            return true;
        },
        validatePersonalInfo() {
            const firstName = document.getElementById('payer_first_name').value;
            const lastName = document.getElementById('payer_last_name').value;
            const email = document.getElementById('payer_email').value;
            const documentNumber = document.getElementById('payer.identification.number').value;
            const identificationType = document.getElementById('payer_identification_type').value;

            if (!firstName || !lastName || !email || !documentNumber || !identificationType) {
                alert('Preencha todos os campos');
                return false;
            }

            return true;
        },
        validatePaymentMethod() {
            const paymentMethod = document.getElementById('payment_method').value;

            if (!paymentMethod) {
                alert('Selecione um método de pagamento');
                return false;
            }

            return true;
        },
        buildPaymentForm() {
            if (this.paymentMethod === 'card') {
                this.buildCardForm();
            }

            if (this.paymentMethod === 'pix') {
                this.buildPixForm();
            }

            this.paymentMethodChose = true;
        },
        buildCardForm() {
            const cardForm = window.mp.cardForm({
                amount: coursePrice,
                iframe: true,
                form: {
                    id: "form-checkout",
                    cardNumber: {
                        id: "form-checkout__cardNumber",
                        placeholder: "Número do cartão",
                    },
                    expirationDate: {
                        id: "form-checkout__expirationDate",
                        placeholder: "MM/YY",
                    },
                    securityCode: {
                        id: "form-checkout__securityCode",
                        placeholder: "Código de segurança",
                    },
                    cardholderName: {
                        id: "form-checkout__cardholderName",
                        placeholder: "Titular do cartão",
                    },
                    issuer: {
                        id: "form-checkout__issuer",
                        placeholder: "Banco emissor",
                    },
                    installments: {
                        id: "form-checkout__installments",
                        placeholder: "Parcelas",
                    },
                },
                callbacks: {
                    onFormMounted: error => {
                        if (error) {
                            return console.warn("Form Mounted handling error: ", error);
                        }
                        console.log("Form mounted");
                    },
                    onSubmit: event => {
                        event.preventDefault();

                        const {
                            paymentMethodId: payment_method_id,
                            issuerId: issuer_id,
                            amount,
                            token,
                            installments,
                        } = cardForm.getCardFormData();

                        this.cardForm = {
                            payment_method_id,
                            issuer_id,
                            amount,
                            token,
                            installments,
                        };

                        this.callProcessPaymentRoute();
                    },
                    onFetching: (resource) => {
                        console.log("Fetching resource: ", resource);
                    }
                },
            });
        },
        buildPixForm() {
            console.log('Pix form');
            let checkoutForm = document.getElementById("form-checkout");

            checkoutForm.addEventListener("submit", (e) => {
                e.preventDefault();
                this.callProcessPaymentRoute();
            });
        },
        callProcessPaymentRoute() {
            fetch(processRoute, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(this.getDataToProcessPayment()),
            }).then(response => response.json()).then(data => {
                this.step = 4;
                this.paymentData = data.data;
            }).catch(error => {
                console.error(error);
            });
        },
        getDataToProcessPayment() {
            if (this.paymentMethod === 'card') {
                return this.getCardDataToProcessPayment();
            }

            if (this.paymentMethod === 'pix') {
                return this.getPixDataToProcessPayment();
            }

            return {};
        },
        getCardDataToProcessPayment() {
            const {
                token,
                issuer_id,
                payment_method_id,
                amount,
                installments,
            } = this.cardForm;

            return {
                token,
                issuer_id,
                payment_method_id,
                transaction_amount: Number(amount),
                installments: Number(installments),
                course_id: courseId,
                payer: {
                    first_name: document.getElementById('payer_first_name').value,
                    last_name: document.getElementById('payer_last_name').value,
                    email: document.getElementById('payer_email').value,
                    identification: {
                        type: document.getElementById(
                            'payer_identification_type').value,
                        number: document.getElementById(
                            'payer.identification.number').value,
                    },
                },
            };
        },
        getPixDataToProcessPayment() {
            return {
                payment_method_id: 'pix',
                course_id: courseId,
                payer: {
                    first_name: document.getElementById('payer_first_name').value,
                    last_name: document.getElementById('payer_last_name').value,
                    email: document.getElementById('payer_email').value,
                    identification: {
                        type: document.getElementById(
                            'payer_identification_type').value,
                        number: document.getElementById(
                            'payer.identification.number').value,
                    },
                },
            };
        },
    }
}
