<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Your Available Quiz') }}</h2>
    </x-slot>

    @if (count($quizzes) > 0)
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-full flex flex-wrap lg:[&>*:nth-child(3n)]:pr-0 sm:[&>*:nth-child(-n+2)]:pt-0 lg:[&>*:nth-child(-n+3)]:pt-0">
            @foreach($quizzes as $quiz)
            <div class="basis-full sm:basis-1/2 lg:basis-1/3 sm:pr-4 pt-4 first:pt-0 sm:max-lg:even:pr-0">
                <a href="{{ route('quizzes.show', $quiz->id) }}">
                    <div class="bg-gray-200 p-6 hover:pt-3 hover:pb-3 active:bg-gray-100 h-48 sm:h-56 flex flex-col justify-between">
                        <div>
                            <h2 class="text-2xl font-bold line-clamp-2">{{ $quiz->name }}</h2>
                            <p class="text-gray-600">{{ $quiz->questions_count }} {{ __('questions') }}</p>
                        </div>
                        
                        <div class="flex justify-between">
                            <p class="text-gray-600">{{ $quiz->duration }} {{ __('minutes') }}</p>
                            <div><span class="mr-2" aria-hidden="true">{{ __('Open') }}</span><x-icon.arrow-sm-right class="before:content-['Open'] inline-block align-text-top w-5 h-5" /></div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="py-6 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-600">{{ __("You didn't have any quiz") }}</div>
    @endif  
</x-app-layout>