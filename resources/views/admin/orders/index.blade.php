<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Pedidos
        </h2>
        <a href="{{ route('dashboard') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Voltar para o Dashboard
                </a>
        </div>
    </x-slot>

    <div class="py-12 px-4" x-data="{
        order: null,
        statusTranslated: null,
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-8">
                <div class="flex items-end">
                    <div class="grow mr-4">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Pesquise pela nome, documento ou email do cliente, ou pelo curso">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status do
                            pagamento</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <option value="">Todos</option>
                            <option value="pending" {{ request('status') == 'authorized' ? 'selected' : '' }}>
                                Autorizado
                            </option>
                            <option value="completed" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                Aprovado
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelado
                            </option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Pendente
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filtrar</button>
                    <a href="{{ route('admin.orders.index') }}"
                        class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Limpar</a>
                </div>
            </form>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Curso
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status do pagamento
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data do pedido
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valor
                            </th>
                            <th width="100px" scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->course->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->student->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->status_translated }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    {{ $order->payments_sum_amount ? 'R$ ' . number_format($order->payments_sum_amount, 2, ',', '.') : 'R$ 0,00' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        @click="$dispatch('open-modal', 'order_details_modal'); order = @js($order); order['status_translated'] = @js($order->status_translated)"
                                        class="text-blue-600 hover:text-blue-900" type="button">
                                        Ver detalhes
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Nenhum pedido encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4 p-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>

        <x-modal id="order_details_modal" name="order_details_modal" x-data maxWidth="lg">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl lg:text-2xl font-bold">
                        Detalhes do Pedido
                    </h1>

                    <button @click="$dispatch('close')" class="text-gray-500 hover:text-gray-700">
                        <i class="ri-close-large-fill ri-xl"></i>
                    </button>
                </div>

                <div class="flex flex-col mt-4">
                    <div class="mt-4">
                        <h2 class="text-lg font-semibold">Curso</h2>
                        <p class="text-gray-700" x-text="order?.course?.name"></p>
                    </div>

                    <div class="mt-4">
                        <h2 class="text-lg font-semibold">Cliente</h2>
                        <p class="text-gray-700" x-text="order?.student?.first_name + ' ' + order?.student?.last_name">
                        </p>
                    </div>

                    <div class="mt-4">
                        <h2 class="text-lg font-semibold">Status do pagamento</h2>
                        <p class="text-gray-700" x-text="order?.status_translated"></p>
                    </div>

                    <div class="mt-4">
                        <h2 class="text-lg font-semibold">Data do pedido</h2>
                        <p class="text-gray-700"
                            x-text="order?.created_at ? new Date(order.created_at).toLocaleDateString('pt-BR') : ''">
                        </p>
                    </div>

                    <div class="mt-4">
                        <h2 class="text-lg font-semibold">Valor</h2>
                        <p class="text-gray-700"
                            x-text="order?.payments_sum_amount ? 'R$ ' + order.payments_sum_amount.toFixed(2).replace('.', ',') : 'R$ 0,00'">
                        </p>
                    </div>

                    <template x-if="order?.payments?.length > 0">
                        <div class="mt-4">
                            <h2 class="text-lg font-semibold">Pagamentos</h2>
                            <div class="flex mt-2">
                                <template x-for="payment in order.payments" :key="payment.id">
                                    <div class="bg-gray-100 p-4 rounded-lg shadow-md mr-4 mb-4">
                                        <p class="text-sm text-gray-700 font-semibold">
                                            Valor: <span x-text="payment.amount ? 'R$ ' + (Number(payment.amount)).toFixed(2).replace('.', ',') : 'R$ 0,00'"></span>
                                        </p>
                                        <p class="text-sm text-gray-700">
                                            Data: <span x-text="payment.created_at ? new Date(payment.created_at).toLocaleDateString('pt-BR') : ''"></span>
                                        </p>
                                        <p class="text-sm text-gray-700">
                                            Método: <span x-text="payment.type"></span>
                                        </p>
                                        <p class="text-sm text-gray-700">
                                            Status: <span :class="{
                                                'bg-blue-100 text-blue-800': payment.status === 'authorized',
                                                'bg-green-100 text-green-800': payment.status === 'approved',
                                                'bg-red-100 text-red-800': payment.status === 'canceled',
                                                'bg-yellow-100 text-yellow-800': payment.status === 'pending',
                                            }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800" x-text="payment.status"></span>
                                        </p>
                                    </div>
                                </template>
                                </ul>
                            </div>
                    </template>
                </div>
            </div>
        </x-modal>
    </div>

</x-app-layout>
