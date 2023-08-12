<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="my-4">
            <h1 class="text-4xl font-bold mb-6">{{ $result->quiz->name }}</h1>
            <p class="text-gray-600 mb-6">{{ $result->quiz->description }}</p>
            <div class="flex flex-row justify-between">
                <div>
                    <p class="my-1 text-lg md:text-xl font-bold text-gray-600"><x-icon.clock class="inline-block mr-1 md:mr-3 align-text-top w-5 md:w-6 h-5 md:h-6" /><span>{{ Carbon\CarbonInterval::seconds($result->completed_duration/1000)->cascade() }}</span></p>
                    <p class="my-1 text-lg md:text-xl font-bold text-gray-600"><x-icon.user class="inline-block mr-1 md:mr-3 align-text-top w-5 md:w-6 h-5 md:h-6" /><span>{{ $result->user->name }}</span></p>
                    <p class="my-1 text-lg md:text-xl font-bold text-gray-600"><x-icon.calendar class="inline-block mr-1 md:mr-3 align-text-top w-5 md:w-6 h-5 md:h-6" /><span>{{ $result->completed_at->timezone('Asia/Jakarta')->format('l\, j F o H:i:s \G\M\T P') }}</span></p>
                </div>
                <p class="my-1 text-2xl md:text-4xl font-bold text-gray-600 justify-self-end"><x-icon.check-circle class="inline-block -mb-1 align-text-top w-9 md:w-14 h-9 md:h-14" /><span class="align-middle">{{ $result->score }}%</span></p>
            </div>
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
                            <p class="mb-4">{{ $question->context }}</p>
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
                                    class="mt-1 text-gray-600 focus:ring-gray-400"
                                    disabled
                                    >
                                <label for="{{ 'option-' . $option->id }}">{{ $option->body }} <span aria-hidden="true">@if($option->is_answer)✓@elseif($checked)✗@endif</span></label>  
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