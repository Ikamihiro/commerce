<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Módulos
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.modules.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Criar novo Módulo
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="GET" action="{{ route('admin.modules.index') }}" class="mb-4 px-6 py-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar módulos..."
                        class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-300 w-full">
                </form>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nome
                            </th>
                            <th scope="col"
                                class="hidden sm:table-cell px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descrição
                            </th>
                            <th scope="col"
                                class="hidden sm:table-cell px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Carga horária
                            </th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($modules as $module)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $module->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $module->name }}
                                </td>
                                <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $module->description_collapsed }}
                                </td>
                                <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $module->class_hours }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.modules.edit', $module) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        <i class="ri-edit-line ri-xl"></i>
                                    </a>
                                    <form action="{{ route('admin.modules.destroy', $module) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="ri-delete-bin-line ri-xl"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4">
                    {{ $modules->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
