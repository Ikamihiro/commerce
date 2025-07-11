<x-landing-layout>
    <section class="pt-16 pb-16 text-gray-700">
        <h2 class="text-3xl md:text-5xl font-semibold text-center mb-8">{{ $course->name }}</h2>
        <span class="block w-full text-center text-lg md:text-3xl mb-8">{{ $course->description }}</span>

        <div class="flex items-center justify-center">
            <div class="w-full md:w-[60%]">
                <x-embed :url="$course->video_url" />
            </div>
        </div>

        <div class="flex justify-center mt-8">
            @if ($course->purchase_released)
                <a href="{{ route('courses.checkout.index', $course->id) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded text-xl md:text-2xl">
                    {{ $course->buy_button_text ?? 'Comprar curso' }}
                </a>
            @else
                <button type="button" disabled="disabled"
                    class="bg-gray-500 text-white font-bold py-4 px-6 rounded text-xl md:text-2xl">
                    {{ $course->buy_button_text ?? 'Comprar curso' }}
                </button>
            @endif
        </div>

        <div class="mt-20 pt-20 border-t border-gray-200">
            <h3 class="text-3xl font-semibold text-center mb-8">
                O que você vai aprender
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($course->modules as $module)
                    <div class="relative bg-white shadow-md rounded-lg p-6">
                        <h4 class="text-xl font-semibold mb-4">>> {{ $module->name }}</h4>
                        <p class="text-gray-700">{{ $module->description }}</p>
                        <div class="text-sm text-gray-700 mt-4">Carga horária: {{ $module->class_hours }} horas</div>
                        <div class="absolute right-3 top-3">
                            <div class="text-xs">Módulo {{ $module->pivot->order }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-20 pt-20 border-t border-gray-200">
            <h3 class="text-3xl font-semibold text-center mb-8">
                Perguntas frequentes
            </h3>
            <div class="space-y-4">
                @foreach ($course->questions as $question)
                    <div x-data="{ open: false }" class="bg-white border border-gray-300 rounded-lg">
                        <button @click="open = !open"
                            class="w-full text-left px-6 py-4 flex justify-between items-center">
                            <span class="text-xl font-semibold">{{ $question->title }}</span>
                            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 12H4m8 8V4" />
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="px-6 py-4 text-gray-700">
                            {{ $question->answer }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-landing-layout>
