<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Módulos
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.courses.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Criar novo Módulo
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
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
                                class="hidden md:block px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descrição
                            </th>
                            <th scope="col"
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($courses as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $course->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $course->name }}
                                </td>
                                <td class="hidden md:block px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $course->description_collapsed }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex">
                                    <a href="{{ route('admin.courses.edit', $course) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        <i class="ri-edit-line ri-xl"></i>
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900">
                                            <i class="ri-delete-bin-line ri-xl"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
