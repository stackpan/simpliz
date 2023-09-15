<x-app-layout>
    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:absolute flex lg:flex-col justify-between items-start">
            <h2
                class="inline-block px-4 py-1 font-bold text-2xl sm:text-3xl bg-neutral text-primary-content/80 text-center rounded">{{ $questions->currentPage() }}</h2>
            <p class="lg:mt-8 font-bold">
                <x-icon.clock class="inline-block lg:block md:w-6 lg:w-10 md:h-6 lg:h-10 text-primary-content/20"/>
                <span id="countdownTimer" class="text-primary-content/80 align-middle">00:00</span></p>
        </div>
        <div class="sm:max-w-xl lg:max-w-3xl mx-auto lg:px-8">
            <section class="my-4 lg:mt-0 leading-snug sm:leading-tight sm:text-lg">
                @isset($questions[0]->context)
                    <p class="mb-4">{{ $questions[0]->context }}</p>
                @endisset
                <p>{{ $questions[0]->body }}</p>
            </section>
            <section class="my-4 leading-snug sm:leading-tight sm:text-lg">
                <livewire:question-options
                    :options="$questions[0]->options->toArray()"
                    :quizSession="$quizSession->toArray()"
                    :questionId="$questions[0]->id"
                />
            </section>
            <div class="mt-12 flex flex-col sm:flex-row-reverse sm:justify-between gap-4">
                @if($questions->onLastPage())
                    <div>
                        <x-button.primary type="button" class="w-full" id="complete"
                                          onclick="confirmModal.showModal()">{{ __('Complete') }}</x-button.primary>
                    </div>
                    <dialog id="confirmModal" class="modal modal-middle">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg">{{ __('Confirmation') }}</h3>
                            <p class="py-4">{{ __('Are you sure to finish the quiz?') }}</p>
                            <div class="modal-action">
                                <form action="{{ route('quiz_sessions.complete', $quizSession->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <x-button.primary id="confirmYes">{{ __('Yes') }}</x-button.primary>
                                </form>
                                <form method="dialog">
                                    <!-- if there is a button in form, it will close the modal -->
                                    <x-button.primary class="btn">{{ __('No') }}</x-button.primary>
                                </form>
                            </div>
                        </div>
                    </dialog>

                    @vite(['resources/js/completeQuizConfirmDialog'])
                @else
                    <a href="{{ $questions->nextPageUrl() }}">
                        <x-button.primary type="button" class="btn-block">{{ __('Next') }}</x-button.primary>
                    </a>
                @endif

                @if(!$questions->onFirstPage())
                    <a href="{{ $questions->previousPageUrl() }}">
                        <x-button.secondary type="button" class="btn-block sm:btn-neutral">{{ __('Previous') }}</x-button.secondary>
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

            const diff = +new Date(`{!! $quizSession->ends_at->toISOString() !!}`) - +new Date();

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

</x-app-layout>
