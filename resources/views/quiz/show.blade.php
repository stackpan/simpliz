<x-app-layout>
    <div>
        <h1 class="text-3xl font-bold">{{ $quiz->name }}</h1>
        <p>{{ $quiz->description }}</p>
        <p>{{ $quiz->questions_count }} {{ __('Questions') }}</p>
        <p>{{ $quiz->duration }} {{ __('Minutes') }}</p>
    </div>
    @if(count($quiz->results))
    <div>
        <h2>Your Results</h2>
        <ul>
            @foreach($quiz->results as $result)
            <li><span></span><span>{{ date('d F Y H:i', strtotime($result->created_at)) }}</span> <a href="{{ route('results.show', $result->id) }}">{{ __('details') }}</a></li>
                @if($result->quizSession !== null)
                    @php $lastQuizSession = $result->quizSession @endphp
                @endif
            @endforeach
        </ul>
    </div>
    @endif
    <div>
        <a href="{{ route('quizzes.index') }}">
            <button type="button">{{ __('Back') }}</button>
        </a>
        @isset($lastQuizSession)
        <a href="{{ route('quiz_sessions.continue', $lastQuizSession->id) }}">{{ __('Continue') }}</a>
        @else
        <form action="{{ route('quiz_sessions.start') }}" method="post">
            @csrf
            <input type="hidden" name="quizId" value="{{ $quiz->id }}">
            <button type="submit">{{ __('Start') }}</button>
        </form>
        @endisset
    </div>
</x-app-layout>