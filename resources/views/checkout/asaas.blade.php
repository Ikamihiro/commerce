<x-checkout-layout>
    <div x-data="{
        loading: false,
        errors: {},
        documentNumber: '',
        documentType: 'CPF',
        init() {
            this.$watch('documentType', (value) => {
                this.documentNumber = '';
                this.errors['student_document_number'] = false;
            });

            this.$watch('documentNumber', (value) => {
                if (this.documentType === 'CPF' && value.length !== 14) {
                    this.errors['student_document_number'] = true;
                } else if (this.documentType === 'CNPJ' && value.length !== 18) {
                    this.errors['student_document_number'] = true;
                } else {
                    this.errors['student_document_number'] = false;
                }
            });

            this.buildForm();
        },
        formatDocumentNumber() {
            if (this.documentType === 'CPF') {
                return '999.999.999-99';
            }

            if (this.documentType === 'CNPJ') {
                return '99.999.999/9999-99';
            }
            return '';
        },
        buildForm() {
            let checkoutForm = document.getElementById('form-checkout');
            checkoutForm.addEventListener('submit', (event) => {
                event.preventDefault();

                this.loading = true;
                if (!this.validateForm()) {
                    this.loading = false;
                    return;
                }

                event.target.submit();
            });
        },
        validateForm() {
            this.errors = {};

            const firstName = document.getElementById('student_first_name').value.trim();
            const lastName = document.getElementById('student_last_name').value.trim();
            const email = document.getElementById('student_email').value.trim();
            const phone = document.getElementById('student_phone').value.trim();
            const documentType = this.documentType;
            const documentNumber = this.documentNumber.trim();

            if (!firstName) {
                this.errors['student_first_name'] = true;
            }
            if (!lastName) {
                this.errors['student_last_name'] = true;
            }
            if (!email) {
                this.errors['student_email'] = true;
            }
            if (!phone) {
                this.errors['student_phone'] = true;
            }
            if (!documentType) {
                this.errors['student_document_type'] = true;
            }
            if (!documentNumber || this.errors['student_document_number']) {
                this.errors['student_document_number'] = true;
            }

            return Object.keys(this.errors).length === 0;
        }
    }">
        <div class="flex items-center mb-8">
            <div class="w-[6rem] h-[6rem]">
                <img src="{{ $course->cover_url }}" alt="{{ $course->name }}"
                    class="inset-0 h-full transform transition-transform duration-300 hover:scale-105 hover:shadow-xl mx-auto rounded">
            </div>
            <div class="grow flex flex-col justify-center ml-4">
                <h1 class="text-2xl font-bold mb-0">{{ $course->name }}</h1>
                <p class="text-sm text-gray-700 ">
                    Parcele em até <strong class="text-lg text-blue-500">10 vezes sem juros</strong>
                </p>
                <p class="text-sm text-gray-700">
                    Ou <strong class="text-md text-blue-500">R$ {{ $course->price }}</strong> à vista
                </p>
            </div>
        </div>

        @include('checkout.components.loading')

        <form id="form-checkout" action="{{ route('courses.checkout.asaas', $course) }}" method="POST" x-transition>
            @csrf

            <h2 class="text-xl font-semibold mb-2">Informações Pessoais</h2>
            <p class="text-gray-700 mb-4">Preencha suas informações pessoais para continuar.</p>

            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input type="text" id="student_first_name" name="student[first_name]"
                        class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Primeiro nome" />
                    <div x-show="errors['student_first_name']">
                        <p class="text-red-500 text-sm mt-1">O nome é obrigatório.</p>
                    </div>
                </div>
                <div>
                    <input type="text" id="student_last_name" name="student[last_name]"
                        class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Sobrenome" />
                    <div x-show="errors['student_last_name']">
                        <p class="text-red-500 text-sm mt-1">O sobrenome é obrigatório.</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <input type="email" id="student_email" name="student[email]"
                    class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="E-mail" />
                <div x-show="errors['student_email']">
                    <p class="text-red-500 text-sm mt-1">O e-mail é obrigatório.</p>
                </div>
            </div>

            <div class="mb-4">
                <input type="text" id="student_phone" name="student[phone]"
                    class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Telefone"
                    x-mask="(99) 99999-9999" />
                <div x-show="errors['student_phone']">
                    <p class="text-red-500 text-sm mt-1">O telefone é obrigatório.</p>
                </div>
            </div>

            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <select id="student_document_type" name="student[document_type]" type="text"
                        class="block w-full border-gray-300 rounded-md shadow-sm" x-model="documentType">
                        <option value="CPF">CPF</option>
                        <option value="CNPJ">CNPJ</option>
                    </select>
                    <div x-show="errors['student_document_type']">
                        <p class="text-red-500 text-sm mt-1">O tipo de documento é obrigatório.</p>
                    </div>
                </div>

                <div>
                    <input type="text" id="student_document_number" name="student[document_number]"
                        class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Número do documento"
                        x-mask:dynamic="formatDocumentNumber()" x-model="documentNumber" />
                    <div x-show="errors['student_document_number']">
                        <p class="text-red-500 text-sm mt-1">O número do documento é obrigatório.</p>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-semibold mb-2 mt-8">Endereço de Cobrança</h2>
            <p class="text-gray-700 mb-4">Preencha seu endereço de cobrança.</p>

            <div class="mb-4">
                <div>
                    <input type="text" id="student_address" name="student[address]"
                        class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Endereço" />
                </div>
            </div>

            <div class="mb-4">
                <div>
                    <input type="text" id="student_address_complement" name="student[address_complement]"
                        class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Complemento" />
                </div>
            </div>

            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input type="text" id="student_address_number" name="student[address_number]"
                        class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Número" />
                </div>
                <div>
                    <input type="text" id="student_postal_code" name="student[postal_code]"
                        class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="CEP"
                        x-mask="99999-999" />
                </div>
            </div>

            <div class="mb-4">
                <input type="text" id="student_city" name="student[city]"
                    class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Cidade" />
            </div>

            <div class="mb-4">
                <input type="text" id="student_state" name="student[state]"
                    class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="Estado" />
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit" id="submit-button"
                    class="bg-green-500 text-white font-bold py-2 px-4 rounded">
                    Ir para o checkout
                </button>
            </div>
        </form>
    </div>
</x-checkout-layout>
