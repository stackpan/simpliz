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
                <form action="{{ route('results.store_user_option', $resultId) }}" method="post">
                    @csrf
                
                    @foreach($questions[0]->options as $option)
                    <input type="radio" name="optionId" id="{{ 'option-'.$option->id }}" value="{{ $option->id }}">
                    <label for="{{ 'option-'.$option->id }}">{{ $option->body }}</label>
                    @endforeach

                    <button type="submit" class="hidden" id="submitBtn"></button>
                </form>
            </section>
            <div>
                <a href="{{ $questions->previousPageUrl() }}">
                    <button type="button">{{ __('Prev') }}</button>
                </a>
                <a href="{{ $questions->nextPageUrl() }}">
                    <button type="button">{{ __('Next') }}</button>
                </a>
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