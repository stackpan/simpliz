<x-app-layout>
    <div>
        <div>{{ $questions->currentPage() }}</div>
        <div>
            <section>
                @isset($questions[0]->context)
                <p>{{ $questions[0]->context }}</p>
                @endisset
                <p>{{ $questions[0]->body }}</p>
            </section>
            <section>
                <form action="{{ route('quiz_sessions.answer', $quizSession->id) }}" method="post">
                    @csrf

                    <input type="hidden" name="userOptionId" value="{{ $questions[0]->pivot->id }}">

                    @foreach($questions[0]->options as $option)
                    <div>
                        <input type="radio" name="optionId" id="{{ 'option-' . $option->id }}" value="{{ $option->id }}"
                            @if($questions[0]->pivot->option_id === $option->id)
                            checked
                            @endif
                            >
                        <label for="{{ 'option-' . $option->id }}">{{ $option->body }}</label>  
                    </div>
                    @endforeach

                    <button type="submit" class="hidden" id="submitBtn"></button>
                </form>
            </section>
            <div>
                @if(!$questions->onFirstPage())
                <a href="{{ $questions->previousPageUrl() }}">
                    <button type="button">{{ __('Previous') }}</button>
                </a>
                @endif
                @if($questions->onLastPage())
                <form action="{{ route('quiz_sessions.complete', $quizSession->id) }}" method="post">
                    @csrf
                    @method('patch')
                    <button type="submit">{{ __('Complete') }}</button>
                </form>
                @else
                <a href="{{ $questions->nextPageUrl() }}">
                    <button type="button">{{ __('Next') }}</button>
                </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const answerRadios = document.querySelectorAll('input[type="radio"][name="optionId"]');
    const submitBtn = document.querySelector('#submitBtn');

    answerRadios.forEach(function(radio) {
        radio.addEventListener('click', function() {
            submitBtn.click();
        });
    });
</script>