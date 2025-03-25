<x-landing-layout>
    <section class="mt-16">
        <h2 class="text-3xl font-semibold text-center mb-8 text-gray-700">Cursos em Destaque</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($courses as $course)
            <div class="text-gray-700 bg-white rounded-lg shadow-lg px-0 text-center h-[22.5rem] mx-auto">
                <img src="{{ $course->cover_url }}" alt="{{ $course->name }}" class="transform transition-transform duration-300 hover:scale-105 hover:shadow-xl mx-auto mb-4">
                <h3 class="text-3xl font-bold mb-4">{{ $course->name }}</h3>
                <a href="{{route('courses.show', $course)}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-xl">Mais Detalhes</a>
            </div>
            @endforeach
        </div>
    </section>
</x-landing-layout>
