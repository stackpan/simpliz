<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-6">
            <h1 class="text-3xl font-bold">{{ __('Your Available Quiz') }}</h1>
        </div>
        <div class="py-6">
            <div class="w-full flex justify-between">
                @foreach($quizzes as $quiz)
                <a href="{{ route('quizzes.show', $quiz->id) }}">
                    <div class="w-64 bg-gray-200">
                        <div>
                            <h2>{{ $quiz->name }}</h2>
                            <p>{{ $quiz->questions_count }} {{ __('questions') }}</p>
                        </div>
                        <div>
                            <p>{{ $quiz->duration }} {{ __('minutes') }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>