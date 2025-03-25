<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Módulo
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('admin.modules.update', $module->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name"
                            class="block text-gray-700 text-sm font-bold mb-2">Nome</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $module->name) }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description"
                            class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
                        <textarea name="description" id="description"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $module->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="class_hours"
                            class="block text-gray-700 text-sm font-bold mb-2">Carga horária</label>
                        <input type="number" name="class_hours" id="class_hours"
                            value="{{ old('class_hours', $module->class_hours) }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                        @error('class_hours')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
