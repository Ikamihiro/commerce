<x-landing-layout>
    <section class="mt-16 pb-16">
        <h2 class="text-3xl font-semibold text-center mb-8">{{ $course->name }}</h2>
        <span class="block w-full text-center text-lg mb-8">{{ $course->description }}</span>

        <x-embed :url="$course->video_url" />

        <div class="flex justify-center mt-8">
            <a href="{{ route('courses.checkout', $course->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Buy Course
            </a>
        </div>
    </section>
</x-landing-layout>
