export function Stepper(processRoute, coursePrice, courseId) {
    return {
        paymentMethod: 'card',
        paymentMethodChose: false,
        paymentData: {},
        cardForm: {},
        loading: false,
        errors: {},
        confirmed: false,
        init() {
            this.selectPaymentMethod('card');
        },
        selectPaymentMethod(method) {
            this.paymentMethod = method;
            // this.paymentMethodChose = true;
            if (this.paymentMethod === 'card') {
                this.buildCardForm();
            }
            if (this.paymentMethod === 'pix') {
                this.buildPixForm();
            }
            // this.paymentMethodChose = true;
        },
        buildCardForm() {
            const cardFormInstance = window.mp.cardForm({
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
                        this.paymentMethodChose = true;
                    },
                    onSubmit: (event) => {
                        event.preventDefault();
                        if (!this.validatePaymentData()) {
                            return;
                        }
                        const {
                            paymentMethodId: payment_method_id,
                            issuerId: issuer_id,
                            amount,
                            token,
                            installments,
                        } = cardFormInstance.getCardFormData();
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
                    },
                    onError: (errorResponse) => {
                        let errors = {};
                        for (let error of errorResponse) {
                            let errorSplited = error['message'].split(' ');
                            errors[errorSplited[0]] = errorSplited.slice(1).join(' ');
                        }
                        this.errors = {
                            ...this.errors,
                            ...errors,
                        };
                    },
                },
            });
        },
        buildPixForm() {
            console.log('Pix form');
            let checkoutForm = document.getElementById("form-checkout");
            checkoutForm.addEventListener("submit", (event) => {
                event.preventDefault();
                if (!this.validatePaymentData()) {
                    return;
                }
                this.callProcessPaymentRoute();
            });
            this.paymentMethodChose = true;
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
                this.confirmed = true;
                this.paymentData = data.data;
                this.loading = false;
                if (this.paymentMethod === 'pix') {
                    setInterval(() => {
                        fetch(`/api/payment/${this.paymentData.id}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        }).then(response => response.json()).then(data => {
                            if (data.status === 'approved') {
                                clearInterval(this.intervalId);
                                this.paymentData.status = 'approved';
                            }
                        }).catch(error => {
                            console.error('Error checking payment status:', error);
                        });
                    }, 10000); // Check every 10 seconds
                }
            });
        },
        validate(fields = []) {
            let errors = {};
            fields.forEach(field => {
                if (!document.getElementById(field).value) {
                    errors[field] = 'Campo obrigatório';
                }
            });
            this.errors = {
                ...this.errors,
                ...errors,
            };
            return Object.keys(errors).length === 0;
        },
        validatePaymentData() {
            return this.validate([
                'payer_first_name',
                'payer_last_name',
                'payer_email',
                'payer_identification_type',
                'payer_identification_number',
            ]) && (this.paymentMethod === 'card' || this.paymentMethod === 'pix');
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
