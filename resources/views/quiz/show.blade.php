<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-col-reverse">
        <div>
            <div class="py-4 mb-6">
                <x-quiz-info :data="$quiz" />
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
                            <td class="text-gray-500 text-end"><a href="{{ route('quiz_sessions.continue', $lastQuizSession->id) }}"><span>{{ __('continue') }}</span><x-icon.arrow-sm-right class="inline-block ml-2" /></a></td>
                            @else
                            <td class="font-bold">{{ $result->score }}%</td>
                            <td>{{ date('d F Y H:i', strtotime($result->created_at)) }}</td>
                            <td class="text-gray-500 text-end"><a href="{{ route('results.show', $result->id) }}"><span>{{ __('details') }}</span><x-icon.arrow-sm-right class="inline-block ml-2" /></a></td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        <div class="mt-12 sm:mt-0 sm:mb-8 flex flex-row justify-between">
            <x-button.link href="{{ route('home') }}"><x-icon.arrow-sm-left class="inline-block mr-2 align-text-top w-6 h-6" /><span>{{ __('Back') }}</span></x-button.link>
            @isset($lastQuizSession)
            <a href="{{ route('quiz_sessions.continue', $lastQuizSession->id) }}"><x-button.secondary type="button">{{ __('Continue') }}</x-button.secondary></a>
            @else
            <form action="{{ route('quiz_sessions.start') }}" method="post">
                @csrf
                <input type="hidden" name="quizId" value="{{ $quiz->id }}">
                <x-button.primary>{{ __('Start') }}</x-button.primary>
            </form>
            @endisset
        </div>
    </div>
</x-app-layout>