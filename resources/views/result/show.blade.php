<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="my-4">
            <x-quiz-info :data="$result->quiz" :username="$result->user->name" :score="$result->score" />
        </div>

        <div class="my-4">
            <ul>
                @php $number = $questions->firstItem() @endphp
                @foreach($questions as $question)
                <li class="my-12 flex flex-col sm:flex-row sm:gap-4">
                    <div>
                        <h2 class="inline-block px-4 py-1 font-bold text-2xl sm:text-3xl bg-gray-200 text-gray-600 text-center">{{ $number }}</h2>
                    </div>
                    <div>
                        <div class="mb-4 mt-4 sm:mt-0 leading-snug sm:leading-tight sm:text-lg">
                            @isset($question->context)
                            <p class="mb-4">{{ $questions[0]->context }}</p>
                            @endisset
                            <p>{{ $question->body }}</p>
                        </div>
                        <div class="my-4 leading-snug sm:leading-tight sm:text-lg">
                        @foreach($question->options as $option)
                        <div class="flex my-2 gap-2">
                                @php $checked = false @endphp
                                <input type="radio"
                                    @if($question->pivot->option_id === $option->id)
                                    @php $checked = true @endphp
                                    checked
                                    @endif
                                    class="mt-1"
                                    disabled
                                    >
                                <label for="{{ 'option-' . $option->id }}">{{ $option->body }} <span aria-hidden="true">@if($option->answer !== null)✓@elseif($checked)✗@endif</span></label>  
                            </div>
                        @endforeach
                        </div>
                    </div>
                    @php $number++ @endphp
                </li>
                @endforeach
            </ul>
            {{ $questions->links() }}
        </div>

        <div class="my-4 md:mt-8 flex justify-center sm:justify-start">
            <x-button.link href="{{ route('quizzes.show', $result->quiz->id) }}"><x-icon.arrow-sm-left class="hidden sm:inline-block mr-2 align-text-top w-6 h-6" /><span class="align-middle">{{ __('Done') }}</span></x-button.link>
        </div>
    </div>
</x-app-layout>