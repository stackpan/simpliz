<x-app-layout>
    <div>
        <h1 class="text-3xl font-bold">{{ $quiz->name }}</h1>
        <p>{{ $quiz->description }}</p>
        <p>{{ $quiz->questions_count }} {{ __('Questions') }}</p>
        <p>{{ $quiz->duration }} {{ __('Minutes') }}</p>
    </div>
    <div>
        <a href="{{ route('quizzes.index') }}">
            <button type="button">{{ __('Back') }}</button>
        </a>
        <form action="{{ route('quiz_session.start', $quiz->id) }}" method="post">
            @csrf
            <button type="submit">{{ __('Start') }}</button>
        </form>
    </div>
</x-app-layout>