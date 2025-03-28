<x-landing-layout>
    <section class="my-16">
        <h2 class="text-3xl font-semibold text-center mb-8 text-gray-700">Cursos em Destaque</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($courses as $course)
                <div
                    class="relative text-gray-700 bg-white rounded-lg shadow-lg px-0 text-center w-[20rem] h-[20rem] transform transition-transform duration-300 hover:scale-105 hover:shadow-xl mx-auto">
                    <img src="{{ $course->cover_url }}" alt="{{ $course->name }}"
                        class="absolute inset-0 h-full transform transition-transform duration-300 hover:scale-105 hover:shadow-xl mx-auto">
                    <div x-data @click='window.location="{{ route('courses.show', $course->id) }}"'
                        class="absolute z-10 inset-0 h-full opacity-100 sm:opacity-0 sm:hover:opacity-100 transition-opacity duration-300 cursor-pointer sm:cursor-default">
                        <div
                            class="p-4 text-white bg-gray-800 bg-opacity-75 flex flex-col justify-end items-center h-full">
                            <h3 class="text-xl font-bold">{{ $course->name }}</h3>
                            <div class="w-full flex flex-wrap justify-around items-center mt-4">
                                <p class="text-sm grow">{{ $course->description_collapsed }}</p>
                                <p class="text-xs mt-6"><i class="ri-time-line mr-2"></i>
                                    {{ $course->total_class_hours }} horas</p>
                                <p class="text-xs mt-6"><i class="ri-tv-2-line mr-2"></i>
                                    {{ $course->total_classes_number }} aulas</p>
                                <p class="text-xs w-full grow mt-4"><i class="ri-team-fill mr-2"></i>
                                    {{ $course->students_number }} assistindo</p>
                                <a href="{{ route('courses.show', $course->id) }}"
                                    class="hidden sm:block px-4 py-2 text-sm text-white mt-4 border border-white rounded hover:bg-white hover:text-black">Mais
                                    detalhes</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-landing-layout>
