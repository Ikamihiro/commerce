<x-landing-layout>
    <section class="mt-16 pb-16">
        <h2 class="text-3xl font-semibold text-center mb-8">{{ $course->name }}</h2>
        <span class="block w-full text-center text-lg mb-8">{{ $course->description }}</span>

        <x-embed :url="$course->video_url" />

        <div class="flex justify-center mt-8">
            <a href="{{ route('courses.checkout', $course->id) }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Buy Course
            </a>
        </div>

        <div class="mt-16">
            <h3 class="text-3xl font-semibold text-center mb-8">{{ __('Course Content') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($course->modules as $module)
                    <div class="relative bg-white shadow-md rounded-lg p-6">
                        <h4 class="text-xl font-semibold mb-4">>> {{ $module->name }}</h4>
                        <p class="text-gray-700">{{ $module->description }}</p>
                        <div class="absolute right-5 top-5">
                            <div class="text-xs">{{__('Module')}} {{$module->pivot->order}}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-landing-layout>
