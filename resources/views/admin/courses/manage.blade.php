<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Course') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Attach a module') }}</h3>
                <div id="attach-module-form" class="">
                    <form action="{{ route('admin.courses.modules.attach', $course) }}" method="POST">
                        @csrf
                        <div class="mb-4 flex justify-between items-center">
                            <span class="text-sm md:text-md">{{ __('Modules') }}:</span>
                            <select class="hidden" id="modules">
                                @foreach ($modules as $module)
                                    @if (!$course->modules->contains($module))
                                        <option value="{{ $module->id }}">
                                            {{ $module->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            <div x-data="dropdown()" x-init="loadOptions()"
                                class="ml-2 w-full flex flex-col items-stretch mx-auto text-sm md:text-md">
                                <input name="modules" type="hidden" x-bind:value="selectedValues()">
                                <div class="inline-block relative w-full">
                                    <div class="flex flex-col items-center relative">
                                        <div x-on:click="open" class="w-full">
                                            <div class="my-2 p-1 flex border-0 bg-transparent rounded">
                                                <div class="flex flex-auto flex-wrap">
                                                    <template x-for="(option,index) in selected"
                                                        :key="options[option].value">
                                                        <div
                                                            class="bg-[#FFBE5D] text-black flex justify-center items-center m-1 font-medium py-2 px-2 rounded-full border-0">
                                                            <div class="text-xs font-normal leading-none max-w-full flex-initial x-model="
                                                                options[option] x-text="options[option].text">
                                                            </div>
                                                            <div class="flex flex-auto flex-row-reverse">
                                                                <div x-on:click.stop="remove(index,option)">
                                                                    <svg class="fill-current h-4 w-4 " role="button"
                                                                        viewBox="0 0 20 20">
                                                                        <path d="M14.348,14.849c-0.469,0.469-1.229,0.469-1.697,0L10,11.819l-2.651,3.029c-0.469,0.469-1.229,0.469-1.697,0
                                                                         c-0.469-0.469-0.469-1.229,0-1.697l2.758-3.15L5.651,6.849c-0.469-0.469-0.469-1.228,0-1.697s1.228-0.469,1.697,0L10,8.183
                                                                         l2.651-3.031c0.469-0.469,1.228-0.469,1.697,0s0.469,1.229,0,1.697l-2.758,3.152l2.758,3.15
                                                                         C14.817,13.62,14.817,14.38,14.348,14.849z" />
                                                                    </svg>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="selected.length == 0" class="flex-1">
                                                        <input placeholder="{{ __('select a module') }}"
                                                            class="border-0 text-end bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800"
                                                            x-bind:value="selectedValues()">
                                                    </div>
                                                </div>
                                                <div
                                                    class="text-gray-300 w-8 py-1 pl-2 pr-1 border-0 flex items-center border-gray-200 svelte-1l8159u">
                                                    <button type="button" x-show="isOpen() === true" x-on:click="open"
                                                        class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                                        <svg version="1.1" class="fill-current h-4 w-4"
                                                            viewBox="0 0 20 20">
                                                            <path d="M17.418,6.109c0.272-0.268,0.709-0.268,0.979,0s0.271,0.701,0,0.969l-7.908,7.83
                                  c-0.27,0.268-0.707,0.268-0.979,0l-7.908-7.83c-0.27-0.268-0.27-0.701,0-0.969c0.271-0.268,0.709-0.268,0.979,0L10,13.25
                                  L17.418,6.109z" />
                                                        </svg>
                                                    </button>
                                                    <button type="button" x-show="isOpen() === false" @click="close"
                                                        class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                                        <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                            <path d="M2.582,13.891c-0.272,0.268-0.709,0.268-0.979,0s-0.271-0.701,0-0.969l7.908-7.83
                                  c0.27-0.268,0.707-0.268,0.979,0l7.908,7.83c0.27,0.268,0.27,0.701,0,0.969c-0.271,0.268-0.709,0.268-0.978,0L10,6.75L2.582,13.891z
                                  " />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full px-4">
                                            <div x-show.transition.origin.top="isOpen()"
                                                class="absolute shadow top-100 bg-white z-40 w-full left-0 rounded max-h-select"
                                                x-on:click.away="close">
                                                <div class="flex flex-col w-full overflow-y-auto h-fit">
                                                    <template x-for="(option,index) in options" :key="option"
                                                        class="overflow-auto">
                                                        <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-gray-100"
                                                            @click="select(index,$event)">
                                                            <div
                                                                class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                                                <div class="w-full items-center flex justify-between">
                                                                    <div class="mx-2 leading-6" x-model="option"
                                                                        x-text="option.text">
                                                                    </div>
                                                                    <div x-show="option.selected">
                                                                        <svg class="svg-icon" viewBox="0 0 20 20">
                                                                            <path fill="none" d="M7.197,16.963H7.195c-0.204,0-0.399-0.083-0.544-0.227l-6.039-6.082c-0.3-0.302-0.297-0.788,0.003-1.087
                                                          C0.919,9.266,1.404,9.269,1.702,9.57l5.495,5.536L18.221,4.083c0.301-0.301,0.787-0.301,1.087,0c0.301,0.3,0.301,0.787,0,1.087
                                                          L7.741,16.738C7.596,16.882,7.401,16.963,7.197,16.963z">
                                                                            </path>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button onclick="toggleCreateModuleForm()" type="button"
                                class="mr-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-600 disabled:opacity-25 transition">
                                {{ __('Create New Module') }}</button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                                {{ __('Attach Module') }}</button>
                        </div>
                    </form>
                </div>

                <div id="create-module-form" class="mt-4 hidden">
                    <form action="{{ route('admin.courses.modules.createAndAttach', $course) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700">{{ __('Module Name') }}</label>
                            <input type="text" id="name" name="name"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                            <textarea id="description" name="description"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="class_hours"
                                class="block text-sm font-medium text-gray-700">{{ __('Class Hours') }}</label>
                            <input type="number" id="class_hours" name="class_hours"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        </div>
                        <div class="flex justify-end">
                            <button onclick="toggleCreateModuleForm()" type="button"
                                class="mr-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-600 disabled:opacity-25 transition">
                                {{ __('Select Module') }}</button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                                {{ __('Create Module') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Modules') }}</h3>
                <ul class="mt-4 border-t border-gray-200 divide-y divide-gray-200">
                    @foreach ($course->modules as $module)
                        <li class="py-4 flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900"> {{__('Module')}} {{$module->pivot->order}} -
                                    {{ $module->name }}</h4>
                                <p class="text-md text-gray-500">{{ $module->description }}</p>
                                <p class="text-md text-gray-500">{{ __('Class Hours') }}: {{ $module->class_hours }}
                                </p>
                            </div>
                            <div>
                                <form action="{{ route('admin.courses.modules.detach', [$course, $module]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition">
                                        {{ __('Detach') }}
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        function toggleCreateModuleForm() {
            var form = document.getElementById('create-module-form');
            var attachModuleForm = document.getElementById('attach-module-form');
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                attachModuleForm.classList.add('hidden');
            } else {
                form.classList.add('hidden');
                attachModuleForm.classList.remove('hidden');
            }
        }

        function dropdown() {
            return {
                options: [],
                selected: [],
                show: false,
                open() {
                    this.show = true;
                },
                close() {
                    this.show = false;
                },
                isOpen() {
                    return this.show === true;
                },
                select(index, event) {
                    if (!this.options[index].selected == true) {
                        this.options[index].selected = true;
                        this.options[index].element = event.target;
                        this.selected.push(index);

                    } else {
                        this.selected.splice(this.selected.lastIndexOf(index), 1);
                        this.options[index].selected = false
                    }
                },
                remove(index, option) {
                    this.options[option].selected = false;
                    this.selected.splice(index, 1);
                },
                loadOptions() {
                    const options = document.getElementById('modules').options;
                    for (let i = 0; i < options.length; i++) {
                        this.options.push({
                            value: options[i].value,
                            text: options[i].innerText,
                            selected: options[i].getAttribute('selected') != null ? true : false,
                            element: options[i]
                        });
                    }

                    for (let i = 0; i < this.options.length; i++) {
                        if (this.options[i].selected == true) {
                            this.selected.push(i);
                        }
                    }
                },
                selectedValues() {
                    return this.selected.map((option) => {
                        return this.options[option].value;
                    });
                }
            };
        };
    </script>
</x-app-layout>
