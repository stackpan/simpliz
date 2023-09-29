 <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-primary-content/80 leading-tight">
            {{ __('Your available quiz') }}
        </h2>
    </x-slot>

    @if (count($quizzes) > 0)
        <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="w-full flex flex-wrap lg:[&>*:nth-child(3n)]:pr-0 sm:[&>*:nth-child(-n+2)]:pt-0 lg:[&>*:nth-child(-n+3)]:pt-0">
                @foreach($quizzes as $quiz)
                    <div class="basis-full sm:basis-1/2 lg:basis-1/3 sm:pr-4 pt-4 first:pt-0 sm:max-lg:even:pr-0">
                        <div class="card h-56 sm:h-60 bg-neutral text-primary-content">
                            <div class="card-body">
                                <h2 class="card-title">{{ $quiz->name }}</h2>
                                <p>{{ $quiz->questions_count }} {{ __('questions') }}</p>
                                <div class="card-actions justify-between items-center">
                                    <div>{{ $quiz->duration }} {{ __('minutes') }}</div>
                                    <a href="{{ route('quizzes.show', $quiz->id) }}">
                                        <button class="btn btn-ghost">
                                            {{ __('Open') }}
                                            <x-icon.arrow-sm-right
                                                class="before:content-['Open'] inline-block align-text-top w-5 h-5"/>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div
            class="py-6 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-600">{{ __("You didn't have any quiz") }}</div>
    @endif
</x-app-layout>
