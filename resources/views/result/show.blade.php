<x-app-layout>

    <div class="my-4">
        <h1 class="text-3xl font-bold">{{ $result->quiz->name }}</h1>
        <p>{{ $result->quiz->description }}</p>
        <ul>
            <li>
                <p>{{ $result->quiz->questions_count }} {{ __('Questions') }}</p>
            </li>
            <li>
                <p>{{ $result->quiz->duration }} {{ __('Minutes') }}</p>
            </li>
            <li>
                <p>{{ $result->user->name }}</p>
            </li>
            <li>
                <p>{{ $result->score }}%</p>
            </li>
        </ul>
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

</x-app-layout>