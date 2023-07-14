<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="my-4">
            <x-quiz-info :data="$result->quiz" :username="$result->user->name" :score="$result->score" />
        </div>

        <div class="my-4">
            <ul>
                @php $number = $questions->firstItem() @endphp
                @foreach($questions as $question)
                <li>
                    <h3>{{ $number }}</h3>
                    <div class="mb-12">
                        <p class="mb-3">{{ $question->context }}</p>
                        <p>{{ $question->body }}</p>
                    
                        @foreach($question->options as $option)
                        <div>
                            @php $checked = false @endphp
                            <input type="radio"
                                @if($question->pivot->option_id === $option->id)
                                @php $checked = true @endphp
                                checked
                                @endif
                                disabled
                                >
                            <label for="{{ 'option-' . $option->id }}">{{ $option->body }} <span>@if($option->answer !== null)✓@elseif($checked)✗@endif</span></label>  
                        </div>
                        @endforeach
                    </div>
                    @php $number++ @endphp
                </li>
                @endforeach
            </ul>
            {{ $questions->links() }}
        </div>

        <div>
            <a href="{{ route('quizzes.show', $result->quiz->id) }}">
                <button type="button">{{ __('Done') }}</button>
            </a>
        </div>
    </div>
</x-app-layout>