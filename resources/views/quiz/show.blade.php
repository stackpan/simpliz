<x-app-layout>

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-col-reverse">

        <div>

            @if($errors->hasBag('user_already_take_quiz'))
                @php $error = $errors->getBag('user_already_take_quiz'); @endphp
                <div>
                    <p class="text-error">{{ $error->first('body') }}</p>
                    <a href="{{ $error->first('last_session_url') }}"
                       class="text-info font-bold"><span>Go back to your work</span>
                        <x-heroicon-s-arrow-small-left class="inline-block align-text-top"/>
                    </a>
                </div>
            @endif

            <div class="py-4 mb-6">
                <h1 class="text-4xl font-bold mb-6">{{ $quiz->name }}</h1>
                <p class="text-base-content/80 mb-6">{{ $quiz->description }}</p>
                <div class="flex flex-col justify-between text-base-content/80">
                    <p class="my-1 text-lg md:text-xl font-bold">
                        <x-heroicon-s-clipboard-document-list
                            class="inline-block mr-1 md:mr-3 align-text-top w-5 md:w-6 h-5 md:h-6"/>
                        <span>{{ $quiz->questions_count }} {{ __('Questions') }}</span></p>
                    <p class="my-1 text-lg md:text-xl font-bold">
                        <x-heroicon-s-clock class="inline-block mr-1 md:mr-3 align-text-top w-5 md:w-6 h-5 md:h-6"/>
                        <span>{{ $quiz->duration }} {{ __('Minutes') }}</span></p>
                </div>
            </div>

            @if(count($quiz->results))
                <div class="py-4">
                    <table class="w-full table-auto">
                        <caption class="text-2xl font-bold mb-3 text-start">{{ __('Your Results') }}</caption>
                        <tbody>
                        @foreach($quiz->results as $result)
                            <tr class="sm:text-lg [&>td]:py-1">
                                @if($result->quizSession !== null)
                                    @php $lastQuizSession = $result->quizSession @endphp
                                    <td class="font-bold text-gray-500">{{ __('In progress') }}</td>
                                    <td></td>
                                    <td class="text-base-content/70 text-end"><a
                                            href="{{ route('quiz_sessions.continue') . '?page=' . $lastQuizSession->last_question_page }}"><span>{{ __('continue') }}</span>
                                            <x-heroicon-s-arrow-small-right class="inline-block ml-2"/>
                                        </a></td>
                                @else
                                    <td class="font-bold">{{ $result->score }}%</td>
                                    <td>{{ $result->completed_at->timezone('Asia/Jakarta')->diffForHumans() }}</td>
                                    <td class="text-base-content/70 text-end">
                                        <a href="{{ route('results.show', $result->id) }}">
                                            <span>{{ __('details') }}</span>
                                            <x-heroicon-s-arrow-small-right class="inline-block ml-2 h-5 w-5"/>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="mt-12 sm:mt-0 sm:mb-8 flex flex-row justify-between">
            <a href="{{ route('home') }}">
                <x-button.link class="px-0">
                    <x-heroicon-s-arrow-small-left class="inline-block mr-2 align-text-top w-6 h-6"/>
                    <span>{{ __('Back') }}</span>
                </x-button.link>
            </a>
            @isset($lastQuizSession)
                <a href="{{ route('quiz_sessions.continue') . '?page=' . $lastQuizSession->last_question_page }}">
                    <x-button.primary type="button">{{ __('Continue') }}</x-button.primary>
                </a>
            @else
                <form action="{{ route('quiz_sessions.start') }}" method="post">
                    @csrf
                    <input type="hidden" name="quizId" value="{{ $quiz->id }}">
                    @if($quiz->is_enabled)
                        <x-button.primary>{{ __('Start') }}</x-button.primary>
                    @else
                        <x-button.primary disabled>{{ __('Disabled') }}</x-button.primary>
                    @endif
                </form>
            @endisset
        </div>
    </div>
</x-app-layout>
