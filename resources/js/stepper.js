export function Stepper(processRoute, coursePrice, courseId) {
    return {
        step: 1,
        paymentMethod: 'card',
        paymentMethodChose: false,
        paymentData: {},
        cardForm: {},
        loading: false,
        errors: {},
        error: null,
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
        validate(fields = []) {
            let errors = {};
            fields.forEach(field => {
                if (!document.getElementById(field).value) {
                    errors[field] = 'Campo obrigatório';
                }
            });
            this.errors = errors;
        },
        validatePersonalInfo() {
            return this.validate([
                'payer_first_name',
                'payer_last_name',
                'payer_email',
                'payer_identification_type',
                'payer_identification_number',
            ]);
        },
        validatePaymentMethod() {
            return this.validate([
                'payment_method',
            ]);
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
            checkoutForm.addEventListener("submit", (event) => {
                event.preventDefault();
                this.callProcessPaymentRoute();
            });
        },
        callProcessPaymentRoute() {
            this.loading = true;
            fetch(processRoute, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify(this.getDataToProcessPayment()),
            }).then(response => {
                return response.json();
            }).then(data => {
                this.step = 4;
                this.paymentData = data.data;
                this.loading = false;
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
                            'payer_identification_number').value,
                    },
                },
            };
        },
        getPixDataToProcessPayment() {
            return {
                payment_method_id: 'pix',
                course_id: courseId,
                transaction_amount: coursePrice,
                payer: {
                    first_name: document.getElementById('payer_first_name').value,
                    last_name: document.getElementById('payer_last_name').value,
                    email: document.getElementById('payer_email').value,
                    identification: {
                        type: document.getElementById(
                            'payer_identification_type').value,
                        number: document.getElementById(
                            'payer_identification_number').value,
                    },
                },
            };
        },
    }
}
