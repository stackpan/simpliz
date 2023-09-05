@inject('userOptionService', 'App\Services\UserOptionService')

<x-app-layout>
    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:absolute flex lg:flex-col justify-between items-start">
            <h2 class="inline-block px-4 py-1 font-bold text-2xl sm:text-3xl bg-gray-200 text-gray-600 text-center">{{ $questions->currentPage() }}</h2>
            <p class="lg:mt-8 font-bold"><x-icon.clock class="inline-block lg:block md:w-6 lg:w-10 md:h-6 lg:h-10 text-gray-300"/><span id="countdownTimer" class="text-gray-600 align-middle">00:00</span></p>
        </div>
        <div class="sm:max-w-xl lg:max-w-3xl mx-auto lg:px-8">
            <section class="my-4 lg:mt-0 leading-snug sm:leading-tight sm:text-lg">
                @isset($questions[0]->context)
                <p class="mb-4">{{ $questions[0]->context }}</p>
                @endisset
                <p>{{ $questions[0]->body }}</p>
            </section>
            <section class="my-4 leading-snug sm:leading-tight sm:text-lg">
                <form action="{{ route('quiz_sessions.answer', $quizSession->id) }}" method="post">
                    @csrf
                    @method('patch')

                    @php
                    $userOption = $userOptionService->getByForeigns($quizSession->result->id, $questions[0]->id);
                    @endphp

                    <input type="hidden" name="userOptionId" value="{{ $userOption->id }}" />
                    <input type="hidden" name="questionPage" value="{{ $questions->currentPage() }}" />

                    @foreach($questions[0]->options as $option)
                    <div class="flex my-2 gap-2">
                        <input type="radio" name="optionId" id="{{ 'option-' . $option->id }}" value="{{ $option->id }}" class="mt-1 text-gray-600 focus:ring-gray-400"
                            @if($userOption->option_id === $option->id)
                            checked
                            @endif
                        >
                        <label for="{{ 'option-' . $option->id }}">{{ $option->body }}</label>
                    </div>
                    @endforeach

                    <button type="submit" class="hidden" id="submitBtn"></button>
                </form>
            </section>
            <div class="mt-12 flex flex-col sm:flex-row-reverse sm:justify-between gap-4">
                @if($questions->onLastPage())
                <div>
                    <x-button.primary type="button" class="w-full" id="complete">{{ __('Complete') }}</x-button.primary>
                </div>

                <div class="absolute hidden max-w-sm sm:mx-auto bg-gray-100 p-6 border-t-4 border-gray-400 left-0 right-0 mx-4" id="confirmDialog">
                    <p class="text-center text-lg mb-4 text-gray-800">{{ __('Are you sure to finish the quiz?') }}</p>
                    <form action="{{ route('quiz_sessions.complete', $quizSession->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <div class="flex flex-row-reverse gap-6">
                            <x-button.secondary class="w-full" id="confirmYes">{{ __('Yes') }}</x-button.secondary>
                            <x-button.secondary type="button" class="w-full" id="confirmNo">{{ __('No') }}</x-button.secondary>
                        </div>
                    </form>
                </div>

                @vite(['resources/js/completeQuizConfirmDialog'])
                @else
                <a href="{{ $questions->nextPageUrl() }}">
                    <x-button.primary type="button" class="w-full">{{ __('Next') }}</x-button.primary>
                </a>
                @endif

                @if(!$questions->onFirstPage())
                <a href="{{ $questions->previousPageUrl() }}">
                    <x-button.secondary type="button" class="w-full">{{ __('Previous') }}</x-button.secondary>
                </a>
                @endif
            </div>
        </div>
    </div>

    <a href="{{ route('quiz_sessions.timeout') }}" id="goToTimeout" class="hidden"></a>

    <script>
        const countdownTimer = document.querySelector("#countdownTimer");
        const goToTimeout = document.querySelector("#goToTimeout");

        countdownTimer.innerHTML = "00:00";

        const countdown = (callback) => {

            const diff = +new Date(`{!! $quizSession->ends_at !!} UTC`) - +new Date();

            let remaining = "";

            if (diff < 0) callback();

            const parts = {
                mins: Math.floor((diff / 1000 / 60) % 60),
                secs: Math.floor((diff / 1000) % 60),
            };

            remaining = Object.keys(parts)
                .map(part => `${parts[part]}`.padStart(2, "0"))
                .join(":");

            countdownTimer.innerHTML = remaining;
        };

        countdown(() => {
            goToTimeout.click();
        });

        const x = setInterval(() => {
            countdown(() => {
                clearInterval(x);
                goToTimeout.click();
            });
        }, 1000);
    </script>

    @vite(['resources/js/optionSubmit'])

</x-app-layout>
