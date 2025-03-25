<x-landing-layout>
    <section class="pt-16 pb-16">
        <h2 class="text-3xl md:text-5xl font-semibold text-center mb-8">{{ $course->name }}</h2>
        <span class="block w-full text-center text-lg md:text-3xl mb-8">{{ $course->description }}</span>

        <div class="flex items-center justify-center">
            <div class="w-full md:w-[60%]">
                <x-embed :url="$course->video_url" />
            </div>
        </div>

        <div class="flex justify-center mt-8">
            <a href="{{ route('courses.checkout', $course->id) }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded text-xl md:text-2xl">
                Compre agora o curso
            </a>
        </div>

        <div class="mt-20 pt-20 border-t border-gray-200">
            <h3 class="text-3xl font-semibold text-center mb-8">
                Conteúdo do curso
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($course->modules as $module)
                    <div class="relative bg-white shadow-md rounded-lg p-6">
                        <h4 class="text-xl font-semibold mb-4">>> {{ $module->name }}</h4>
                        <p class="text-gray-700">{{ $module->description }}</p>
                        <div class="absolute right-3 top-3">
                            <div class="text-xs">Módulo {{$module->pivot->order}}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-landing-layout>
