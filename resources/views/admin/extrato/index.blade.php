<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Extrato financeiro
            </h2>
            <a href="{{ route('dashboard') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar para o Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <div
                    class="bg-gray-100 border border-gray-400 text-gray-700 px-6 py-4 rounded shadow flex items-center">
                    <span class="font-semibold text-lg mr-2">Saldo atual:</span>
                    <span class="text-2xl font-bold">
                        R$ {{ number_format($saldo['balance'], 2, ',', '.') }}
                    </span>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.extratos.index') }}" class="mb-8">
                <div class="flex items-end">

                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filtrar</button>
                    <a href="{{ route('admin.extratos.index') }}"
                        class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Limpar</a>
                </div>
            </form>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            {{-- <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th> --}}
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descrição
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data
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
                        @forelse ($extrato['data'] as $movimentacao)
                            <tr>
                                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movimentacao['id'] }}</td> --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $movimentacao['type'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $movimentacao['description'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $movimentacao['date'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    {{-- {{ $order->payments_sum_amount ? 'R$ ' . number_format($order->payments_sum_amount, 2, ',', '.') : 'R$ 0,00' }} --}}
                                    {{ 'R$ ' . number_format($movimentacao['value'] ?? 0, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Nenhuma movimentação encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($extrato['totalCount'] > $extrato['limit'])
                    @php
                        $currentOffset = $extrato['offset'];
                        $limit = $extrato['limit'];
                        $total = $extrato['totalCount'];
                        $currentPage = intval(floor($currentOffset / $limit)) + 1;
                        $lastPage = intval(ceil($total / $limit));
                    @endphp
                    <div class="mt-4 p-4 flex justify-center">
                        <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
                            {{-- Previous Page Link --}}
                            @if ($currentPage > 1)
                                <a href="{{ request()->fullUrlWithQuery(['offset' => max(0, $currentOffset - $limit), 'limit' => $limit]) }}"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    aria-label="Previous">
                                    &laquo;
                                </a>
                            @else
                                <span
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed"
                                    aria-disabled="true">
                                    &laquo;
                                </span>
                            @endif

                            {{-- Pagination Elements --}}
                            @for ($page = 1; $page <= $lastPage; $page++)
                                @php
                                    $pageOffset = ($page - 1) * $limit;
                                @endphp
                                @if ($page == $currentPage)
                                    <span
                                        class="z-10 bg-blue-500 border-blue-500 text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ request()->fullUrlWithQuery(['offset' => $pageOffset, 'limit' => $limit]) }}"
                                        class="bg-white border-gray-300 text-gray-700 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            {{-- Next Page Link --}}
                            @if ($currentPage < $lastPage)
                                <a href="{{ request()->fullUrlWithQuery(['offset' => $currentOffset + $limit, 'limit' => $limit]) }}"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    aria-label="Next">
                                    &raquo;
                                </a>
                            @else
                                <span
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed"
                                    aria-disabled="true">
                                    &raquo;
                                </span>
                            @endif
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
